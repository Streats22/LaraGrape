<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\MediaField;
use Streats\Atlas\Fields\TextField;

class ImageBlock extends Block
{
    public function name(): string
    {
        return 'image';
    }

    public function fields(): array
    {
        return [
            new MediaField('src', 'Image'),
            new TextField('alt', 'Alt text'),
            new TextField('caption', 'Caption'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'rounded', 'wide'];
    }

    public function rules(): array
    {
        return [
            'src' => ['required', 'string', 'max:2048'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:500'],
        ];
    }
}
