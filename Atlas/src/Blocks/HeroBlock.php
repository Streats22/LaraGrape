<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;
use Streats\Atlas\Fields\TextareaField;

class HeroBlock extends Block
{
    public function name(): string
    {
        return 'hero';
    }

    public function fields(): array
    {
        return [
            new TextField('title', 'Title'),
            new TextareaField('subtitle', 'Subtitle'),
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
            'subtitle' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
