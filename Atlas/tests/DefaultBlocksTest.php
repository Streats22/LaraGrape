<?php

namespace Streats\Atlas\Tests;

use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\DefaultBlocks;

class DefaultBlocksTest extends TestCase
{
    public function test_default_blocks_list_is_non_empty(): void
    {
        $this->assertGreaterThan(10, count(DefaultBlocks::all()));
    }

    public function test_register_default_blocks_registers_all(): void
    {
        app(\Streats\Atlas\AtlasManager::class)->registerDefaultBlocks();

        foreach (DefaultBlocks::all() as $class) {
            $expected = app($class)->name();
            $fromRegistry = BlockRegistry::get($expected);
            $this->assertSame($expected, $fromRegistry->name());
        }
    }
}
