<?php

namespace Streats\Atlas\Observers;

use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\PageRenderCache;

class PageObserver
{
    public function updated(Page $page): void
    {
        PageRenderCache::forget($page->id);
    }

    public function deleted(Page $page): void
    {
        PageRenderCache::forget($page->id);
    }
}
