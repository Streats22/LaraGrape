<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;
use Streats\Atlas\Fields\TextareaField;

class QuoteBlock extends Block
{
    public function name(): string
    {
        return 'quote';
    }

    public function fields(): array
    {
        return [
            new TextareaField('quote', 'Quote'),
            new TextField('author', 'Author'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'large'];
    }

    public function rules(): array
    {
        return [
            'quote' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
        ];
    }
}
