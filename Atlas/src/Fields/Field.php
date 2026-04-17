<?php

namespace Streats\Atlas\Fields;

abstract class Field
{
    public function __construct(
        public string $name,
        public string $label,
    ) {}

    /**
     * Stable identifier for FieldRegistry (e.g. "text", "rich").
     */
    abstract public static function type(): string;

    abstract public function toNova(): mixed;

    abstract public function toFilament(): mixed;
}
