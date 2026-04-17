<?php

namespace Streats\Atlas\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Models\BlockModel;
use Streats\Atlas\Models\Page;

class BlockService
{
    public function delete(BlockModel $block): void
    {
        $pageId = (int) $block->page_id;
        $block->delete();
        $this->renormalizeOrder($pageId);
        PageRenderCache::forget($pageId);
    }

    /**
     * Clone a block and its fields to the end of the page.
     */
    public function duplicate(BlockModel $block): BlockModel
    {
        $page = $block->page;
        $block->loadMissing('fields');

        $maxOrder = (int) $page->blocks()->max('order');
        $new = $page->blocks()->create([
            'type' => $block->type,
            'style' => $block->style,
            'order' => $maxOrder + 1,
        ]);

        foreach ($block->fields as $field) {
            $new->fields()->create([
                'key' => $field->key,
                'value' => $field->value,
            ]);
        }

        PageRenderCache::forget((int) $page->id);

        return $new->fresh(['fields']);
    }

    /**
     * @throws ValidationException
     */
    public function setStyle(BlockModel $block, string $style): void
    {
        $def = BlockRegistry::get($block->type);
        if (! in_array($style, $def->styles(), true)) {
            throw ValidationException::withMessages([
                'style' => ['The selected style is invalid for this block.'],
            ]);
        }

        $block->update(['style' => $style]);
        PageRenderCache::forget((int) $block->page_id);
    }

    protected function renormalizeOrder(int $pageId): void
    {
        $rows = BlockModel::query()
            ->where('page_id', $pageId)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        foreach ($rows as $index => $row) {
            if ((int) $row->order !== $index) {
                $row->update(['order' => $index]);
            }
        }
    }

    public function create(Page $page, string $type): BlockModel
    {
        $styles = BlockRegistry::get($type)->styles();
        $style = $styles[0] ?? 'default';

        return $page->blocks()->create([
            'type' => $type,
            'style' => $style,
            'order' => $page->blocks()->count(),
        ]);
    }

    /**
     * Insert a new block at a 0-based position on the page (visual order).
     */
    public function createAt(Page $page, string $type, int $index): BlockModel
    {
        if (! BlockRegistry::has($type)) {
            throw new \InvalidArgumentException("Unknown block type: {$type}");
        }

        $styles = BlockRegistry::get($type)->styles();
        $style = $styles[0] ?? 'default';

        $new = $page->blocks()->create([
            'type' => $type,
            'style' => $style,
            'order' => 999999,
        ]);

        $newId = (int) $new->id;
        $page = $page->fresh();

        $ids = $page->blocks()
            ->orderBy('order')
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $ids = array_values(array_filter($ids, fn (int $id) => $id !== $newId));
        $insertAt = max(0, min($index, count($ids)));
        array_splice($ids, $insertAt, 0, [$newId]);

        $this->reorder($ids);

        PageRenderCache::forget((int) $page->id);

        return $new->fresh(['fields']);
    }

    /**
     * @param  array<string, mixed>  $data
     *
     * @throws ValidationException
     */
    public function saveFields(BlockModel $block, array $data): void
    {
        $blockClass = BlockRegistry::get($block->type);

        $rules = $blockClass->rules();
        if ($rules !== []) {
            Validator::make($data, $rules)->validate();
        }

        $data = $blockClass->mutateBeforeSave($data);

        foreach ($data as $key => $value) {
            $block->fields()->updateOrCreate(
                ['key' => $key],
                ['value' => is_string($value) ? $value : json_encode($value)]
            );
        }

        PageRenderCache::forget($block->page_id);
    }

    /**
     * @param  list<int|string>  $orderedIds
     */
    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            BlockModel::query()->whereKey($id)->update(['order' => $index]);
        }

        $first = BlockModel::query()->find($orderedIds[0] ?? null);
        if ($first !== null) {
            PageRenderCache::forget($first->page_id);
        }
    }
}
