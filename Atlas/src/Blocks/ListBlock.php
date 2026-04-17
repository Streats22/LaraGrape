<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextareaField;

class ListBlock extends Block
{
    public function name(): string
    {
        return 'list';
    }

    public function fields(): array
    {
        return [
            new TextareaField('items', 'Items (one per line)'),
        ];
    }

    public function styles(): array
    {
        return ['bullet', 'numbered'];
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'string'],
        ];
    }
}
