<?php

namespace Streats\Atlas;

use Streats\Atlas\Blocks\Block;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Blocks\DefaultBlocks;
use Streats\Atlas\Fields\FieldRegistry;

class AtlasManager
{
    /**
     * @param  class-string<Block>  $class
     */
    public function registerBlock(string $class): void
    {
        BlockRegistry::register($class);
    }

    /**
     * @param  class-string<\Streats\Atlas\Fields\Field>  $class
     */
    public function registerField(string $class): void
    {
        FieldRegistry::register($class);
    }

    /**
     * Register the full set of built-in blocks (headings, hero, cards, etc.).
     */
    public function registerDefaultBlocks(): void
    {
        foreach (DefaultBlocks::all() as $class) {
            BlockRegistry::register($class);
        }
    }
}
