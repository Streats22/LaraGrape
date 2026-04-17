<?php

namespace Streats\Atlas\Adapters;

use Streats\Atlas\Contracts\AtlasAdapter;
use Streats\Atlas\Repositories\SettingsRepository;

class AdapterManager
{
    public function __construct(
        protected SettingsRepository $settings
    ) {}

    public function boot(): void
    {
        $candidates = config('atlas.adapters', []);
        if (! is_array($candidates)) {
            return;
        }

        $enabledFilter = $this->settings->getEnabledAdapterClasses();
        if ($enabledFilter !== null) {
            $candidates = array_values(array_filter(
                $candidates,
                fn ($adapter) => is_string($adapter) && in_array($adapter, $enabledFilter, true)
            ));
        }

        foreach ($candidates as $adapter) {
            if (! is_string($adapter) || ! class_exists($adapter)) {
                continue;
            }

            $instance = app($adapter);

            if ($instance instanceof AtlasAdapter) {
                $instance->boot();
            }
        }
    }
}
