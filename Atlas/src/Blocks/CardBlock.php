<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;
use Streats\Atlas\Fields\TextareaField;

class CardBlock extends Block
{
    public function name(): string
    {
        return 'card';
    }

    public function fields(): array
    {
        return [
            new TextField('title', 'Title'),
            new TextareaField('body', 'Body'),
            new TextField('link_label', 'Link label'),
            new TextField('link_url', 'Link URL'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'bordered', 'elevated'];
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'link_label' => ['nullable', 'string', 'max:120'],
            'link_url' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
