<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextareaField;

class ParagraphBlock extends Block
{
    public function name(): string
    {
        return 'paragraph';
    }

    public function fields(): array
    {
        return [
            new TextareaField('body', 'Body'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'prose'];
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string'],
        ];
    }
}
