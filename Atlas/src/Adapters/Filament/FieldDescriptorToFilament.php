<?php

namespace Streats\Atlas\Adapters\Filament;

/**
 * Maps portable field descriptors (from {@see \Streats\Atlas\Fields\Field}) to Filament form components.
 * Requires filament/filament (or filament/forms) in the consuming application.
 */
class FieldDescriptorToFilament
{
    public static function from(mixed $descriptor): mixed
    {
        if (! is_array($descriptor)) {
            return null;
        }

        if (($descriptor['driver'] ?? '') !== 'filament') {
            return null;
        }

        if (! class_exists(\Filament\Forms\Components\TextInput::class)) {
            return null;
        }

        $name = $descriptor['name'] ?? '';
        $label = $descriptor['label'] ?? $name;

        return match ($descriptor['type'] ?? '') {
            'text', 'media' => \Filament\Forms\Components\TextInput::make($name)->label($label),
            default => \Filament\Forms\Components\TextInput::make($name)->label($label),
        };
    }

    /**
     * @param  array<int, mixed>  $descriptors
     */
    public static function many(array $descriptors): array
    {
        $out = [];
        foreach ($descriptors as $d) {
            $c = static::from($d);
            if ($c !== null) {
                $out[] = $c;
            }
        }

        return $out;
    }
}
