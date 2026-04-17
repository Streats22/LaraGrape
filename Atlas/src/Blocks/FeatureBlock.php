<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;
use Streats\Atlas\Fields\TextareaField;

class FeatureBlock extends Block
{
    public function name(): string
    {
        return 'feature';
    }

    public function fields(): array
    {
        return [
            new TextField('icon', 'Icon (emoji or short text)'),
            new TextField('title', 'Title'),
            new TextareaField('description', 'Description'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'horizontal'];
    }

    public function rules(): array
    {
        return [
            'icon' => ['nullable', 'string', 'max:32'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
