<?php

namespace Streats\Atlas\Tests;

use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\HeadingBlock;
use Streats\Atlas\Services\SystemDetection;

class SystemDetectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        BlockRegistry::register(HeadingBlock::class);
    }

    public function test_snapshot_includes_core_keys(): void
    {
        $snap = app(SystemDetection::class)->snapshot();

        $this->assertArrayHasKey('php', $snap);
        $this->assertArrayHasKey('laravel', $snap);
        $this->assertArrayHasKey('livewire', $snap);
        $this->assertArrayHasKey('filament', $snap);
        $this->assertArrayHasKey('nova', $snap);
        $this->assertGreaterThanOrEqual(1, $snap['block_types']);
    }
}
