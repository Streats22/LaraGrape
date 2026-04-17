<?php

namespace Streats\Atlas\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Streats\Atlas\Adapters\AdapterManager;
use Streats\Atlas\Contracts\AtlasAdapter;
use Streats\Atlas\Repositories\SettingsRepository;

class AdapterATestAdapter implements AtlasAdapter
{
    public static int $bootCount = 0;

    public function boot(): void
    {
        self::$bootCount++;
    }
}

class AdapterBTestAdapter implements AtlasAdapter
{
    public static int $bootCount = 0;

    public function boot(): void
    {
        self::$bootCount++;
    }
}

class AdapterManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        AdapterATestAdapter::$bootCount = 0;
        AdapterBTestAdapter::$bootCount = 0;
    }

    public function test_boots_all_config_adapters_when_settings_unset(): void
    {
        config()->set('atlas.adapters', [
            AdapterATestAdapter::class,
            AdapterBTestAdapter::class,
        ]);

        app(AdapterManager::class)->boot();

        $this->assertSame(1, AdapterATestAdapter::$bootCount);
        $this->assertSame(1, AdapterBTestAdapter::$bootCount);
    }

    public function test_respects_enabled_filter_from_database(): void
    {
        config()->set('atlas.adapters', [
            AdapterATestAdapter::class,
            AdapterBTestAdapter::class,
        ]);

        app(SettingsRepository::class)->setEnabledAdapterClasses([
            AdapterBTestAdapter::class,
        ]);

        app(AdapterManager::class)->boot();

        $this->assertSame(0, AdapterATestAdapter::$bootCount);
        $this->assertSame(1, AdapterBTestAdapter::$bootCount);
    }
}
