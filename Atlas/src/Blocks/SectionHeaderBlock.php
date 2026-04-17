<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;
use Streats\Atlas\Fields\TextareaField;

class SectionHeaderBlock extends Block
{
    public function name(): string
    {
        return 'section_header';
    }

    public function fields(): array
    {
        return [
            new TextField('eyebrow', 'Eyebrow'),
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
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
        ];
    }
}
