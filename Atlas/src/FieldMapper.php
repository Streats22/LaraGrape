<?php

namespace Streats\Atlas;

use Illuminate\Support\Collection;
use Streats\Atlas\Fields\Field;

class FieldMapper
{
    /**
     * @param  array<int, Field>  $fields
     * @return Collection<int, mixed>
     */
    public static function toNova(array $fields): Collection
    {
        return collect($fields)->map(fn (Field $field) => $field->toNova());
    }

    /**
     * @param  array<int, Field>  $fields
     * @return Collection<int, mixed>
     */
    public static function toFilament(array $fields): Collection
    {
        return collect($fields)->map(fn (Field $field) => $field->toFilament());
    }
}
