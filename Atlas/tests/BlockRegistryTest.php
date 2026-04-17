<?php

namespace Streats\Atlas\Tests;

use InvalidArgumentException;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\HeadingBlock;

class BlockRegistryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        BlockRegistry::register(HeadingBlock::class);
    }

    public function test_get_returns_registered_block(): void
    {
        $block = BlockRegistry::get('heading');

        $this->assertSame('heading', $block->name());
    }

    public function test_unknown_type_throws(): void
    {
        $this->expectException(InvalidArgumentException::class);

        BlockRegistry::get('missing');
    }
}
