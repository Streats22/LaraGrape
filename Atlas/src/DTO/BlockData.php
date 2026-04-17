<?php

namespace Streats\Atlas\DTO;

/**
 * @phpstan-type BlockFields array<string, mixed>
 */
class BlockData
{
    /**
     * @param  BlockFields  $fields
     */
    public function __construct(
        public string $type,
        public string $style,
        public array $fields,
    ) {}

    public function __get(string $key): mixed
    {
        return $this->fields[$key] ?? null;
    }
}
