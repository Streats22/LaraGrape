<?php

namespace Streats\Atlas\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\HeadingBlock;
use Streats\Atlas\Models\BlockModel;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\BlockService;
use Streats\Atlas\Services\RevisionService;

class RevisionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        BlockRegistry::register(HeadingBlock::class);
    }

    public function test_snapshot_persists_structure(): void
    {
        $page = Page::query()->create(['title' => 'T', 'slug' => 't']);
        $block = app(BlockService::class)->create($page, 'heading');
        app(BlockService::class)->saveFields($block, ['title' => 'Hi']);

        $rev = app(RevisionService::class)->snapshot($page->fresh(['blocks.fields']), 'v1');

        $this->assertSame('v1', $rev->label);
        $this->assertArrayHasKey('blocks', $rev->snapshot);
        $this->assertCount(1, $rev->snapshot['blocks']);
        $this->assertSame('heading', $rev->snapshot['blocks'][0]['type']);
    }

    public function test_page_has_revisions_relation(): void
    {
        $page = Page::query()->create(['title' => 'T', 'slug' => 't2']);
        /** @var BlockModel $b */
        $b = app(BlockService::class)->create($page, 'heading');
        app(BlockService::class)->saveFields($b, ['title' => 'X']);
        app(RevisionService::class)->snapshot($page, 'snap');

        $this->assertCount(1, $page->fresh()->revisions);
    }
}
