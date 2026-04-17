<?php

namespace Streats\Atlas\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\HeadingBlock;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\BlockService;

/**
 * Smoke coverage for package HTTP routes when `atlas.routes.enabled` is true.
 *
 * Browser QA (manual): Chrome and Safari — drag from palette, reorder via grip,
 * double-click append, live editor drawer open/close and drops on canvas.
 */
class AtlasRoutesSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('atlas.routes.enabled', true);
    }

    protected function setUp(): void
    {
        parent::setUp();
        BlockRegistry::register(HeadingBlock::class);
    }

    public function test_dashboard_route_returns_ok_when_registered(): void
    {
        $this->get('/atlas/dashboard')->assertOk();
    }

    public function test_live_editor_route_returns_ok_for_page_with_blocks(): void
    {
        $page = Page::query()->create(['title' => 'L', 'slug' => 'live-smoke']);
        app(BlockService::class)->create($page, 'heading');

        $this->get('/atlas/pages/'.$page->id.'/live')->assertOk();
    }
}
