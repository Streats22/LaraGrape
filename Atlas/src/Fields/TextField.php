<?php

namespace Streats\Atlas\Fields;

/**
 * Portable text field. Adapters map these descriptors to Nova/Filament components.
 */
class TextField extends Field
{
    public function __construct(
        string $name = 'title',
        string $label = 'Title',
    ) {
        parent::__construct($name, $label);
    }

    public static function type(): string
    {
        return 'text';
    }

    public function toNova(): mixed
    {
        return [
            'driver' => 'nova',
            'type' => 'text',
            'name' => $this->name,
            'label' => $this->label,
        ];
    }

    public function toFilament(): mixed
    {
        return [
            'driver' => 'filament',
            'type' => 'text',
            'name' => $this->name,
            'label' => $this->label,
        ];
    }
}
