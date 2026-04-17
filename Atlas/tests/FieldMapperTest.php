<?php

namespace Streats\Atlas\Tests;

use Streats\Atlas\FieldMapper;
use Streats\Atlas\Fields\TextField;

class FieldMapperTest extends TestCase
{
    public function test_to_nova_maps_fields(): void
    {
        $fields = [new TextField('title', 'Title')];
        $mapped = FieldMapper::toNova($fields);

        $this->assertCount(1, $mapped);
        $this->assertSame('nova', $mapped->first()['driver']);
    }

    public function test_to_filament_maps_fields(): void
    {
        $fields = [new TextField('title', 'Title')];
        $mapped = FieldMapper::toFilament($fields);

        $this->assertCount(1, $mapped);
        $this->assertSame('filament', $mapped->first()['driver']);
    }
}
