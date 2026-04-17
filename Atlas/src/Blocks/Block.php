<?php

namespace Streats\Atlas\Blocks;

abstract class Block
{
    abstract public function name(): string;

    /**
     * @return array<int, \Streats\Atlas\Fields\Field>
     */
    abstract public function fields(): array;

    /**
     * @return array<int, string>
     */
    abstract public function styles(): array;

    /**
     * Laravel validation rules keyed by field name.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateBeforeSave(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateAfterLoad(array $data): array
    {
        return $data;
    }
}
