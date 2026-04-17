<?php

namespace Streats\Atlas\Observers;

use Streats\Atlas\Models\BlockField;
use Streats\Atlas\Models\BlockModel;
use Streats\Atlas\Services\PageRenderCache;

class BlockFieldObserver
{
    public function saved(BlockField $field): void
    {
        $this->forgetPageCache($field);
    }

    public function deleted(BlockField $field): void
    {
        $this->forgetPageCache($field);
    }

    protected function forgetPageCache(BlockField $field): void
    {
        $pageId = BlockModel::query()->whereKey($field->block_id)->value('page_id');
        if ($pageId !== null) {
            PageRenderCache::forget((int) $pageId);
        }
    }
}
