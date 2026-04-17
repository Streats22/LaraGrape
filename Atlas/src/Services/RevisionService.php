<?php

namespace Streats\Atlas\Services;

use Streats\Atlas\Models\Page;
use Streats\Atlas\Models\PageRevision;

class RevisionService
{
    /**
     * Store a JSON snapshot of the page tree (page attributes + blocks + field rows).
     */
    public function snapshot(Page $page, ?string $label = null): PageRevision
    {
        $page->load(['blocks.fields']);

        $snapshot = [
            'page' => $page->only(['id', 'title', 'slug', 'updated_at']),
            'blocks' => $page->blocks->map(function ($block) {
                return [
                    'id' => $block->id,
                    'type' => $block->type,
                    'style' => $block->style,
                    'order' => $block->order,
                    'fields' => $block->fields->mapWithKeys(fn ($f) => [$f->key => $f->value])->all(),
                ];
            })->values()->all(),
        ];

        return PageRevision::query()->create([
            'page_id' => $page->id,
            'snapshot' => $snapshot,
            'label' => $label,
        ]);
    }
}
