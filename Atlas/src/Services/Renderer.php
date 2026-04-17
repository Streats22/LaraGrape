<?php

namespace Streats\Atlas\Services;

use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewContract;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\DTO\BlockData;
use Streats\Atlas\Models\BlockModel;
use Streats\Atlas\Models\Page;

class Renderer
{
    public function render(Page $page): string
    {
        $page->loadMissing('blocks.fields');

        return $page->blocks->map(fn (BlockModel $block) => $this->renderBlock($block))->implode('');
    }

    public function renderBlock(BlockModel $block): string
    {
        $blockData = $this->buildBlockData($block);
        $viewName = $this->viewName($block->type, $block->style ?? 'default');
        $html = View::make($viewName, ['block' => $blockData])->render();

        return $this->wrapPublishedHtml($html, $block);
    }

    /**
     * Preview output while editing: merges unsaved field values over stored data.
     *
     * @param  array<string, mixed>  $fieldOverrides
     */
    public function renderBlockWithOverrides(BlockModel $block, array $fieldOverrides = []): string
    {
        $block->loadMissing('fields');
        $blockClass = BlockRegistry::get($block->type);
        $raw = $this->decodeFieldValues($block);
        $merged = array_merge($raw, $fieldOverrides);
        $data = $blockClass->mutateAfterLoad($merged);
        $style = $block->style ?? 'default';
        $blockData = new BlockData(
            $block->type,
            $style,
            $data
        );
        $viewName = $this->viewName($block->type, $style);

        $html = View::make($viewName, ['block' => $blockData])->render();

        return $this->wrapPublishedHtml($html, $block);
    }

    /**
     * Wraps rendered block HTML for the live editor only (data attributes for DnD / selection).
     * Public pages should use {@see renderBlock()} without this wrapper.
     *
     * @param  array<string, mixed>  $fieldOverrides  When non-empty, preview uses merged field values.
     */
    public function renderBlockWrappedForEditor(BlockModel $block, array $fieldOverrides = []): string
    {
        $inner = $fieldOverrides === []
            ? $this->renderBlock($block)
            : $this->renderBlockWithOverrides($block, $fieldOverrides);

        $type = htmlspecialchars((string) $block->type, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $id = (int) $block->id;

        return '<div class="atlas-editor-block-root" data-atlas-block-id="'.$id.'" data-id="'.$id.'" data-atlas-type="'.$type.'">'.$inner.'</div>';
    }

    /**
     * Public / preview HTML: semantic section + stable classes for styling and QA.
     */
    protected function wrapPublishedHtml(string $html, BlockModel $block): string
    {
        if (! config('atlas.rendering.wrap_blocks', true)) {
            return $html;
        }

        $typeSlug = $this->cssToken((string) $block->type);
        $styleSlug = $this->cssToken((string) ($block->style ?? 'default'));
        $extra = trim((string) config('atlas.rendering.wrapper_extra_class', ''));
        $classes = trim("atlas-block atlas-block--{$typeSlug} atlas-block-style-{$styleSlug} ".$extra);
        $id = (int) $block->id;
        $typeAttr = htmlspecialchars((string) $block->type, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $label = htmlspecialchars(str_replace('_', ' ', (string) $block->type).' block', ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return '<section class="'.$classes.'" data-atlas-block="'.$id.'" data-atlas-type="'.$typeAttr.'" aria-label="'.$label.'">'.$html.'</section>';
    }

    protected function cssToken(string $value): string
    {
        $s = strtolower(str_replace('_', '-', preg_replace('/[^A-Za-z0-9_-]/', '', $value)));

        return $s !== '' ? $s : 'block';
    }

    public function blockView(BlockModel $block): ViewContract
    {
        $blockData = $this->buildBlockData($block);
        $viewName = $this->viewName($block->type, $block->style ?? 'default');

        return View::make($viewName, ['block' => $blockData]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function decodeFieldValues(BlockModel $block): array
    {
        return $block->fields->pluck('value', 'key')->map(function ($value) {
            if ($value === null) {
                return null;
            }
            $decoded = json_decode((string) $value, true);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
        })->toArray();
    }

    protected function buildBlockData(BlockModel $block): BlockData
    {
        $blockClass = BlockRegistry::get($block->type);
        $raw = $this->decodeFieldValues($block);
        $data = $blockClass->mutateAfterLoad($raw);

        return new BlockData(
            $block->type,
            $block->style ?? 'default',
            $data
        );
    }

    protected function viewName(string $type, string $style): string
    {
        $namespace = config('atlas.view_namespace', 'atlas::blocks');

        return "{$namespace}.{$type}.{$style}";
    }
}
