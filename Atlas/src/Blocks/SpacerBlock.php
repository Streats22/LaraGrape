<?php

namespace Streats\Atlas\Blocks;

class SpacerBlock extends Block
{
    public function name(): string
    {
        return 'spacer';
    }

    public function fields(): array
    {
        return [];
    }

    public function styles(): array
    {
        return ['sm', 'md', 'lg', 'xl'];
    }
}
