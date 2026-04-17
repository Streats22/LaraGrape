<?php

namespace Streats\Atlas\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\HeadingBlock;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\BlockService;

class BlockServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        BlockRegistry::register(HeadingBlock::class);
    }

    public function test_save_fields_validates_and_stores_values(): void
    {
        $page = Page::query()->create(['title' => 'T', 'slug' => 't']);
        $block = app(BlockService::class)->create($page, 'heading');

        app(BlockService::class)->saveFields($block, ['title' => 'OK']);

        $this->assertSame('OK', $block->fields()->where('key', 'title')->value('value'));
    }

    public function test_save_fields_throws_on_validation_failure(): void
    {
        $this->expectException(ValidationException::class);

        $page = Page::query()->create(['title' => 'T', 'slug' => 't2']);
        $block = app(BlockService::class)->create($page, 'heading');

        app(BlockService::class)->saveFields($block, ['title' => '']);
    }

    public function test_delete_removes_block_and_renormalizes_order(): void
    {
        $page = Page::query()->create(['title' => 'T', 'slug' => 'del']);
        $b0 = app(BlockService::class)->create($page, 'heading');
        $b1 = app(BlockService::class)->create($page, 'heading');

        app(BlockService::class)->delete($b0);

        $this->assertDatabaseMissing('atlas_blocks', ['id' => $b0->id]);
        $this->assertSame(0, (int) $b1->fresh()->order);
    }

    public function test_duplicate_copies_fields(): void
    {
        $page = Page::query()->create(['title' => 'T', 'slug' => 'dup']);
        $block = app(BlockService::class)->create($page, 'heading');
        app(BlockService::class)->saveFields($block, ['title' => 'Original']);

        $copy = app(BlockService::class)->duplicate($block);

        $this->assertNotSame($block->id, $copy->id);
        $this->assertSame('Original', $copy->fields()->where('key', 'title')->value('value'));
    }

    public function test_set_style_validates_against_block_styles(): void
    {
        $this->expectException(ValidationException::class);

        $page = Page::query()->create(['title' => 'T', 'slug' => 'sty']);
        $block = app(BlockService::class)->create($page, 'heading');

        app(BlockService::class)->setStyle($block, 'not-a-real-style');
    }

    public function test_set_style_updates_row(): void
    {
        $page = Page::query()->create(['title' => 'T', 'slug' => 'sty2']);
        $block = app(BlockService::class)->create($page, 'heading');

        app(BlockService::class)->setStyle($block, 'centered');

        $this->assertSame('centered', $block->fresh()->style);
    }

    public function test_create_at_inserts_at_index(): void
    {
        $page = Page::query()->create(['title' => 'T', 'slug' => 'idx']);
        $a = app(BlockService::class)->create($page, 'heading');
        $b = app(BlockService::class)->create($page, 'heading');

        app(BlockService::class)->saveFields($a, ['title' => 'A']);
        app(BlockService::class)->saveFields($b, ['title' => 'B']);

        $mid = app(BlockService::class)->createAt($page->fresh(), 'heading', 1);
        app(BlockService::class)->saveFields($mid, ['title' => 'MID']);

        $titles = $page->fresh()->blocks()->orderBy('order')->get()->map(function ($block) {
            return $block->fields()->where('key', 'title')->value('value');
        })->all();

        $this->assertSame(['A', 'MID', 'B'], $titles);
    }
}
