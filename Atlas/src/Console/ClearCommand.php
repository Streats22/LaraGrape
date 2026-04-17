<?php

namespace Streats\Atlas\Console;

use Illuminate\Console\Command;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\PageRenderCache;

class ClearCommand extends Command
{
    protected $signature = 'atlas:clear';

    protected $description = 'Clear Atlas page render cache';

    public function handle(): int
    {
        $count = 0;

        Page::query()->chunkById(50, function ($pages) use (&$count) {
            foreach ($pages as $page) {
                PageRenderCache::forget($page->id);
                $count++;
            }
        });

        $this->info("Cleared cache keys for {$count} page(s).");

        return self::SUCCESS;
    }
}
