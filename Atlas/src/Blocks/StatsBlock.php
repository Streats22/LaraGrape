<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;

class StatsBlock extends Block
{
    public function name(): string
    {
        return 'stats';
    }

    public function fields(): array
    {
        return [
            new TextField('label', 'Label'),
            new TextField('value', 'Value'),
            new TextField('suffix', 'Suffix'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'compact'];
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:120'],
            'value' => ['required', 'string', 'max:64'],
            'suffix' => ['nullable', 'string', 'max:32'],
        ];
    }
}
