<?php

namespace Streats\Atlas\Services;

use Illuminate\Support\Facades\Cache;

class PageRenderCache
{
    public static function key(int $pageId): string
    {
        return "atlas_page_{$pageId}";
    }

    public static function forget(int $pageId): void
    {
        Cache::forget(static::key($pageId));
    }

    public static function ttl(): int
    {
        return (int) config('atlas.cache_ttl', 3600);
    }
}
