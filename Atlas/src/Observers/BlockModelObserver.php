<?php

namespace Streats\Atlas\Observers;

use Streats\Atlas\Models\BlockModel;
use Streats\Atlas\Services\PageRenderCache;

class BlockModelObserver
{
    public function saved(BlockModel $block): void
    {
        PageRenderCache::forget($block->page_id);
    }

    public function deleted(BlockModel $block): void
    {
        PageRenderCache::forget($block->page_id);
    }
}
