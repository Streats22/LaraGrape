<?php

namespace Streats\Atlas\Fields;

use InvalidArgumentException;

class FieldRegistry
{
    /** @var array<string, class-string<Field>> */
    protected static array $fields = [];

    /**
     * @param  class-string<Field>  $class
     */
    public static function register(string $class): void
    {
        static::$fields[$class::type()] = $class;
    }

    /**
     * Resolve a field instance. Pass constructor args if your field needs them.
     *
     * @param  array<int, mixed>  $parameters
     */
    public static function make(string $type, array $parameters = []): Field
    {
        if (! isset(static::$fields[$type])) {
            throw new InvalidArgumentException("Unknown field type: {$type}");
        }

        $class = static::$fields[$type];

        return empty($parameters)
            ? app($class)
            : app()->makeWith($class, $parameters);
    }

    /**
     * @return array<string, class-string<Field>>
     */
    public static function all(): array
    {
        return static::$fields;
    }

    public static function has(string $type): bool
    {
        return isset(static::$fields[$type]);
    }
}
