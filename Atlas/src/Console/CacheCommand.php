<?php

namespace Streats\Atlas\Console;

use Illuminate\Console\Command;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\CachedRenderer;

class CacheCommand extends Command
{
    protected $signature = 'atlas:cache';

    protected $description = 'Warm Atlas page render cache';

    public function handle(CachedRenderer $renderer): int
    {
        $count = 0;

        Page::query()->chunkById(50, function ($pages) use ($renderer, &$count) {
            foreach ($pages as $page) {
                $renderer->render($page);
                $count++;
            }
        });

        $this->info("Warmed cache for {$count} page(s).");

        return self::SUCCESS;
    }
}
