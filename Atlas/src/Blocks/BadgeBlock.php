<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;

class BadgeBlock extends Block
{
    public function name(): string
    {
        return 'badge';
    }

    public function fields(): array
    {
        return [
            new TextField('text', 'Text'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'primary', 'success', 'outline'];
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:120'],
        ];
    }
}
