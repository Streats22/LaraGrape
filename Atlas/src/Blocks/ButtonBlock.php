<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;

class ButtonBlock extends Block
{
    public function name(): string
    {
        return 'button';
    }

    public function fields(): array
    {
        return [
            new TextField('label', 'Label'),
            new TextField('url', 'URL'),
        ];
    }

    public function styles(): array
    {
        return ['primary', 'secondary', 'outline', 'ghost'];
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:2048'],
        ];
    }
}
