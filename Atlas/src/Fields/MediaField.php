<?php

namespace Streats\Atlas\Fields;

/**
 * Stores a media identifier (e.g. disk path or Spatie media id) as a string field.
 * Validation and upload UI belong in the host app or Filament/Nova adapters.
 */
class MediaField extends Field
{
    public function __construct(
        string $name = 'asset',
        string $label = 'Media',
    ) {
        parent::__construct($name, $label);
    }

    public static function type(): string
    {
        return 'media';
    }

    public function toNova(): mixed
    {
        return [
            'driver' => 'nova',
            'type' => 'media',
            'name' => $this->name,
            'label' => $this->label,
        ];
    }

    public function toFilament(): mixed
    {
        return [
            'driver' => 'filament',
            'type' => 'media',
            'name' => $this->name,
            'label' => $this->label,
        ];
    }
}
