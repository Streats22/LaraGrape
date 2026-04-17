<?php

namespace Streats\Atlas\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void registerBlock(string $class)
 * @method static void registerField(string $class)
 * @method static void registerDefaultBlocks()
 *
 * @see \Streats\Atlas\AtlasManager
 */
class Atlas extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'atlas';
    }
}
