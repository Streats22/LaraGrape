<?php

namespace Streats\Atlas\Fields;

/**
 * Multi-line text for paragraphs and long copy in the builder inspector.
 */
class TextareaField extends Field
{
    public function __construct(
        string $name = 'body',
        string $label = 'Body',
    ) {
        parent::__construct($name, $label);
    }

    public static function type(): string
    {
        return 'textarea';
    }

    public function toNova(): mixed
    {
        return [
            'driver' => 'nova',
            'type' => 'textarea',
            'name' => $this->name,
            'label' => $this->label,
        ];
    }

    public function toFilament(): mixed
    {
        return [
            'driver' => 'filament',
            'type' => 'textarea',
            'name' => $this->name,
            'label' => $this->label,
        ];
    }
}
