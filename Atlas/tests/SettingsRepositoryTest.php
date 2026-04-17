<?php

namespace Streats\Atlas\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Streats\Atlas\Repositories\SettingsRepository;

class SettingsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_and_get_json_round_trip(): void
    {
        $repo = app(SettingsRepository::class);
        $repo->set('test.key', ['a' => 1]);

        $raw = $repo->get('test.key');
        $this->assertIsString($raw);
        $this->assertSame(['a' => 1], json_decode($raw, true));
    }

    public function test_enabled_adapters_null_when_unset(): void
    {
        $repo = app(SettingsRepository::class);

        $this->assertNull($repo->getEnabledAdapterClasses());
    }

    public function test_enabled_adapters_returns_array_when_set(): void
    {
        $repo = app(SettingsRepository::class);
        $repo->setEnabledAdapterClasses(['Foo\\Bar', 'Baz\\Qux']);

        $this->assertSame(['Foo\\Bar', 'Baz\\Qux'], $repo->getEnabledAdapterClasses());
    }
}
