<?php

namespace Streats\Atlas\Livewire\Concerns;

use Streats\Atlas\Services\BlockService;

/**
 * Use with Livewire + SortableJS: stable wire:key per row, sync order manually.
 */
trait ReordersBlocks
{
    /**
     * @param  list<int|string>  $orderedIds
     */
    public function reorder(array $orderedIds): void
    {
        app(BlockService::class)->reorder($orderedIds);
    }
}
