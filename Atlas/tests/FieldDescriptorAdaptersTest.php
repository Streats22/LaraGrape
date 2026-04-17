<?php

namespace Streats\Atlas\Tests;

use Streats\Atlas\Adapters\Filament\FieldDescriptorToFilament;
use Streats\Atlas\Adapters\Nova\FieldDescriptorToNova;

class FieldDescriptorAdaptersTest extends TestCase
{
    public function test_filament_returns_null_without_filament(): void
    {
        $d = [
            'driver' => 'filament',
            'type' => 'text',
            'name' => 'title',
            'label' => 'Title',
        ];

        if (class_exists(\Filament\Forms\Components\TextInput::class)) {
            $this->assertNotNull(FieldDescriptorToFilament::from($d));
        } else {
            $this->assertNull(FieldDescriptorToFilament::from($d));
        }
    }

    public function test_nova_returns_null_without_nova(): void
    {
        $d = [
            'driver' => 'nova',
            'type' => 'text',
            'name' => 'title',
            'label' => 'Title',
        ];

        if (class_exists(\Laravel\Nova\Fields\Text::class)) {
            $this->assertNotNull(FieldDescriptorToNova::from($d));
        } else {
            $this->assertNull(FieldDescriptorToNova::from($d));
        }
    }
}
