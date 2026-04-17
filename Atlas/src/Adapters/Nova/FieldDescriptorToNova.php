<?php

namespace Streats\Atlas\Adapters\Nova;

/**
 * Maps portable field descriptors to Nova fields.
 * Requires laravel/nova in the consuming application.
 */
class FieldDescriptorToNova
{
    public static function from(mixed $descriptor): mixed
    {
        if (! is_array($descriptor)) {
            return null;
        }

        if (($descriptor['driver'] ?? '') !== 'nova') {
            return null;
        }

        if (! class_exists(\Laravel\Nova\Fields\Text::class)) {
            return null;
        }

        $name = $descriptor['name'] ?? '';
        $label = $descriptor['label'] ?? $name;

        return match ($descriptor['type'] ?? '') {
            'text', 'media' => \Laravel\Nova\Fields\Text::make($label, $name),
            default => \Laravel\Nova\Fields\Text::make($label, $name),
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
