<?php

namespace Streats\Atlas\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\HeadingBlock;
use Streats\Atlas\Models\BlockModel;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\Renderer;

class RendererTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        BlockRegistry::register(HeadingBlock::class);
    }

    public function test_renders_page_blocks(): void
    {
        $page = Page::query()->create([
            'title' => 'Home',
            'slug' => 'home',
        ]);

        /** @var BlockModel $block */
        $block = $page->blocks()->create([
            'type' => 'heading',
            'style' => 'default',
            'order' => 0,
        ]);

        $block->fields()->create(['key' => 'title', 'value' => 'Hello Atlas']);

        $html = app(Renderer::class)->render($page->fresh(['blocks.fields']));

        $this->assertStringContainsString('Hello Atlas', $html);
        $this->assertStringContainsString('text-3xl', $html);
        $this->assertStringContainsString('<section class="atlas-block', $html);
        $this->assertStringContainsString('data-atlas-block="'.$block->id.'"', $html);
    }

    public function test_render_can_disable_block_wrapper(): void
    {
        config(['atlas.rendering.wrap_blocks' => false]);

        $page = Page::query()->create([
            'title' => 'Home',
            'slug' => 'home-nowrap',
        ]);

        $block = $page->blocks()->create([
            'type' => 'heading',
            'style' => 'default',
            'order' => 0,
        ]);
        $block->fields()->create(['key' => 'title', 'value' => 'Hi']);

        $html = app(Renderer::class)->render($page->fresh(['blocks.fields']));

        $this->assertStringNotContainsString('<section class="atlas-block', $html);
    }

    public function test_render_block_with_overrides_uses_unsaved_values(): void
    {
        $page = Page::query()->create([
            'title' => 'Home',
            'slug' => 'home-ov',
        ]);

        /** @var BlockModel $block */
        $block = $page->blocks()->create([
            'type' => 'heading',
            'style' => 'default',
            'order' => 0,
        ]);

        $block->fields()->create(['key' => 'title', 'value' => 'Stored']);

        $html = app(Renderer::class)->renderBlockWithOverrides($block, ['title' => 'Preview']);

        $this->assertStringContainsString('Preview', $html);
        $this->assertStringNotContainsString('Stored', $html);
    }

    public function test_render_block_wrapped_for_editor_includes_block_attributes(): void
    {
        $page = Page::query()->create([
            'title' => 'Home',
            'slug' => 'home-wrap',
        ]);

        /** @var BlockModel $block */
        $block = $page->blocks()->create([
            'type' => 'heading',
            'style' => 'default',
            'order' => 0,
        ]);

        $block->fields()->create(['key' => 'title', 'value' => 'Hi']);

        $html = app(Renderer::class)->renderBlockWrappedForEditor($block->fresh(['fields']));

        $this->assertStringContainsString('data-atlas-block-id="'.$block->id.'"', $html);
        $this->assertStringContainsString('data-id="'.$block->id.'"', $html);
        $this->assertStringContainsString('data-atlas-type="heading"', $html);
        $this->assertStringContainsString('Hi', $html);
    }
}
