<?php

namespace Streats\Atlas\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Streats\Atlas\Repositories\SettingsRepository;
use Streats\Atlas\Services\SystemDetection;

#[Layout('atlas::layouts.livewire-shell')]
#[Title('Atlas setup')]
class SetupWizard extends Component
{
    /** @var list<string> */
    public array $enabledAdapterClasses = [];

    public function mount(SettingsRepository $settings): void
    {
        $configured = config('atlas.adapters', []);
        $configured = is_array($configured) ? array_values(array_filter($configured, 'is_string')) : [];

        $filter = $settings->getEnabledAdapterClasses();

        if ($filter === null) {
            $this->enabledAdapterClasses = $configured;
        } else {
            $this->enabledAdapterClasses = array_values(array_intersect($configured, $filter));
        }
    }

    public function save(SettingsRepository $settings): void
    {
        $configured = config('atlas.adapters', []);
        $configured = is_array($configured) ? array_values(array_filter($configured, 'is_string')) : [];

        $on = array_values(array_intersect($configured, $this->enabledAdapterClasses));

        $settings->setEnabledAdapterClasses($on);

        session()->flash('atlas_setup_message', 'Adapter selection saved. Reload the application (or next request) uses this list.');
    }

    public function clearAdapterFilter(SettingsRepository $settings): void
    {
        $settings->forget(SettingsRepository::KEY_ENABLED_ADAPTERS);
        $this->mount($settings);
        session()->flash('atlas_setup_message', 'Cleared: all config adapters will boot again.');
    }

    public function render(SystemDetection $detection)
    {
        $configuredAdapters = array_values(array_filter(
            config('atlas.adapters', []),
            fn ($c) => is_string($c)
        ));

        return view('atlas::livewire.setup-wizard', [
            'configuredAdapters' => $configuredAdapters,
            'detection' => $detection->snapshot(),
        ]);
    }
}
