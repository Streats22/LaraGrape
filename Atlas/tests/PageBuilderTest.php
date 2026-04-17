<?php

namespace Streats\Atlas\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\HeadingBlock;
use Streats\Atlas\Livewire\PageBuilder;
use Streats\Atlas\Models\Page;

class PageBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        BlockRegistry::register(HeadingBlock::class);
    }

    public function test_add_block_selects_new_block(): void
    {
        $page = Page::query()->create(['title' => 'P', 'slug' => 'pb']);

        Livewire::test(PageBuilder::class, ['page' => $page])
            ->call('addBlockFromPalette', 'heading', 0)
            ->assertSet('selectedBlockId', $page->fresh()->blocks()->first()->id);
    }

    public function test_reorder_updates_order(): void
    {
        $page = Page::query()->create(['title' => 'P', 'slug' => 'pb2']);
        $a = app(\Streats\Atlas\Services\BlockService::class)->create($page, 'heading');
        $b = app(\Streats\Atlas\Services\BlockService::class)->create($page, 'heading');

        Livewire::test(PageBuilder::class, ['page' => $page->fresh()])
            ->call('reorder', [(int) $b->id, (int) $a->id])
            ->assertHasNoErrors();

        $this->assertSame(0, (int) $b->fresh()->order);
        $this->assertSame(1, (int) $a->fresh()->order);
    }

    public function test_save_fields_persists(): void
    {
        $page = Page::query()->create(['title' => 'P', 'slug' => 'pb3']);
        $block = app(\Streats\Atlas\Services\BlockService::class)->create($page, 'heading');

        Livewire::test(PageBuilder::class, ['page' => $page])
            ->call('selectBlock', $block->id)
            ->set('fieldValues.title', 'Saved title')
            ->call('saveFields')
            ->assertHasNoErrors()
            ->assertSet('fieldSaveBanner', true);

        $this->assertSame('Saved title', $block->fresh()->fields()->where('key', 'title')->value('value'));
    }

    public function test_canvas_mode_persists_in_session(): void
    {
        $page = Page::query()->create(['title' => 'P', 'slug' => 'canvas']);

        Livewire::test(PageBuilder::class, ['page' => $page])
            ->call('setCanvasMode', 'preview')
            ->assertSet('canvasMode', 'preview');

        $this->assertSame('preview', session('atlas_canvas_mode'));
    }

    public function test_apply_style_updates_block(): void
    {
        $page = Page::query()->create(['title' => 'P', 'slug' => 'pb4']);
        $block = app(\Streats\Atlas\Services\BlockService::class)->create($page, 'heading');

        Livewire::test(PageBuilder::class, ['page' => $page])
            ->call('selectBlock', $block->id)
            ->call('applyStyle', 'centered')
            ->assertSet('inspectorStyle', 'centered')
            ->assertHasNoErrors();

        $this->assertSame('centered', $block->fresh()->style);
    }
}
