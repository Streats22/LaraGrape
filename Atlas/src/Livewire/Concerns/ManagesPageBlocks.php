<?php

namespace Streats\Atlas\Livewire\Concerns;

use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Fields\Field;
use Streats\Atlas\Models\BlockModel;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\BlockService;

trait ManagesPageBlocks
{
    public int $pageId;

    public ?int $selectedBlockId = null;

    /** @var array<string, mixed> */
    public array $fieldValues = [];

    public string $inspectorStyle = 'default';

    /** @var list<string> */
    public array $blockTypes = [];

    public bool $fieldSaveBanner = false;

    /** @var 'studio'|'preview'|'creative' */
    public string $canvasMode = 'studio';

    public ?int $lastSavedBlockId = null;

    public function restoreCanvasMode(): void
    {
        $mode = session('atlas_canvas_mode', config('atlas.ui.canvas.default_mode', 'studio'));
        $this->canvasMode = in_array($mode, ['studio', 'preview', 'creative'], true) ? $mode : 'studio';
    }

    public function setCanvasMode(string $mode): void
    {
        if (! in_array($mode, ['studio', 'preview', 'creative'], true)) {
            return;
        }

        $this->canvasMode = $mode;
        session(['atlas_canvas_mode' => $mode]);
    }

    public function clearSavePulse(): void
    {
        $this->lastSavedBlockId = null;
    }

    public function selectBlock(int $blockId): void
    {
        $previousId = $this->selectedBlockId;
        if ($previousId !== null && $previousId !== $blockId) {
            $this->fieldSaveBanner = false;
            $this->lastSavedBlockId = null;
        }

        $this->selectedBlockId = $blockId;
        $block = BlockModel::query()->with('fields')->findOrFail($blockId);
        $this->inspectorStyle = $block->style ?? 'default';
        $this->fieldValues = $block->fields->pluck('value', 'key')->map(function ($v) {
            $d = json_decode((string) $v, true);

            return json_last_error() === JSON_ERROR_NONE ? $d : $v;
        })->toArray();
    }

    public function clearSelection(): void
    {
        $this->selectedBlockId = null;
        $this->fieldValues = [];
        $this->inspectorStyle = 'default';
        $this->fieldSaveBanner = false;
        $this->lastSavedBlockId = null;
    }

    public function applyStyle(string $style, BlockService $blocks): void
    {
        if ($this->selectedBlockId === null) {
            return;
        }

        $this->inspectorStyle = $style;
        $block = BlockModel::query()->findOrFail($this->selectedBlockId);
        $blocks->setStyle($block, $style);
    }

    public function saveFields(BlockService $blocks): void
    {
        if ($this->selectedBlockId === null) {
            return;
        }

        $block = BlockModel::query()->findOrFail($this->selectedBlockId);
        $blocks->saveFields($block, $this->fieldValues);
        $this->selectBlock($this->selectedBlockId);
        $this->fieldSaveBanner = true;
        $this->lastSavedBlockId = $this->selectedBlockId;
    }

    public function deleteBlock(int $blockId, BlockService $blocks): void
    {
        $block = BlockModel::query()->findOrFail($blockId);
        if ((int) $block->page_id !== $this->pageId) {
            return;
        }

        $blocks->delete($block);

        if ($this->selectedBlockId === $blockId) {
            $this->clearSelection();
        }
    }

    public function duplicateBlock(int $blockId, BlockService $blocks): void
    {
        $block = BlockModel::query()->findOrFail($blockId);
        if ((int) $block->page_id !== $this->pageId) {
            return;
        }

        $new = $blocks->duplicate($block);
        $this->selectBlock($new->id);
    }

    /**
     * @param  list<int|string>  $orderedIds
     */
    public function reorder(array $orderedIds, BlockService $blocks): void
    {
        $blocks->reorder($orderedIds);
    }

    /**
     * Called when a block type is dropped from the palette onto the canvas (Sortable onAdd).
     */
    public function addBlockFromPalette(string $type, int $index, BlockService $blocks): void
    {
        if ($type === '' || ! BlockRegistry::has($type)) {
            return;
        }

        $page = Page::query()->findOrFail($this->pageId);
        $new = $blocks->createAt($page, $type, $index);
        $this->selectBlock($new->id);
    }

    /**
     * @return list<array{key: string, label: string, type: string}>
     */
    public function schemaForBlock(BlockModel $block): array
    {
        $def = BlockRegistry::get($block->type);
        $out = [];
        foreach ($def->fields() as $field) {
            if ($field instanceof Field) {
                $out[] = [
                    'key' => $field->name,
                    'label' => $field->label,
                    'type' => $field::type(),
                ];
            }
        }

        return $out;
    }

    /**
     * @return list<string>
     */
    public function stylesForBlock(BlockModel $block): array
    {
        return BlockRegistry::get($block->type)->styles();
    }
}
