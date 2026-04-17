<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;
use Streats\Atlas\Fields\TextareaField;

class AlertBlock extends Block
{
    public function name(): string
    {
        return 'alert';
    }

    public function fields(): array
    {
        return [
            new TextField('title', 'Title'),
            new TextareaField('message', 'Message'),
        ];
    }

    public function styles(): array
    {
        return ['info', 'success', 'warning', 'danger'];
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
        ];
    }
}
