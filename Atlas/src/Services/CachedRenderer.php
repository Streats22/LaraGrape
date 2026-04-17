<?php

namespace Streats\Atlas\Services;

use Illuminate\Support\Facades\Cache;
use Streats\Atlas\Models\Page;

class CachedRenderer extends Renderer
{
    public function render(Page $page): string
    {
        return Cache::remember(
            PageRenderCache::key($page->id),
            now()->addSeconds(PageRenderCache::ttl()),
            fn () => parent::render($page)
        );
    }
}
