<?php

namespace Streats\Atlas\Blocks;

use InvalidArgumentException;

class BlockRegistry
{
    /** @var array<string, class-string<Block>> */
    protected static array $blocks = [];

    /**
     * @param  class-string<Block>  $class
     */
    public static function register(string $class): void
    {
        /** @var Block $instance */
        $instance = app($class);
        static::$blocks[$instance->name()] = $class;
    }

    public static function get(string $type): Block
    {
        if (! isset(static::$blocks[$type])) {
            throw new InvalidArgumentException("Unknown block type: {$type}");
        }

        $class = static::$blocks[$type];

        return app($class);
    }

    /**
     * @return array<string, class-string<Block>>
     */
    public static function all(): array
    {
        return static::$blocks;
    }

    public static function has(string $type): bool
    {
        return isset(static::$blocks[$type]);
    }
}
