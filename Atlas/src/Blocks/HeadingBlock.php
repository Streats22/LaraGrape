<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;

/**
 * Example block for documentation and tests.
 */
class HeadingBlock extends Block
{
    public function name(): string
    {
        return 'heading';
    }

    public function fields(): array
    {
        return [
            new TextField('title', 'Title'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'centered'];
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
        ];
    }
}
