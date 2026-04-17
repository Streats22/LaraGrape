<?php

namespace Streats\Atlas\Blocks;

class DividerBlock extends Block
{
    public function name(): string
    {
        return 'divider';
    }

    public function fields(): array
    {
        return [];
    }

    public function styles(): array
    {
        return ['default', 'thick', 'gradient'];
    }
}
