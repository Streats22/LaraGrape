<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextareaField;

class TwoColumnBlock extends Block
{
    public function name(): string
    {
        return 'two_column';
    }

    public function fields(): array
    {
        return [
            new TextareaField('left', 'Left column'),
            new TextareaField('right', 'Right column'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'stack'];
    }

    public function rules(): array
    {
        return [
            'left' => ['required', 'string'],
            'right' => ['required', 'string'],
        ];
    }
}
