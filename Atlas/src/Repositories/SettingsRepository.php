<?php

namespace Streats\Atlas\Repositories;

use Illuminate\Support\Facades\Schema;
use Streats\Atlas\Models\Setting;

class SettingsRepository
{
    public const KEY_ENABLED_ADAPTERS = 'atlas.enabled_adapters';

    public function has(string $key): bool
    {
        if (! $this->tableExists()) {
            return false;
        }

        return Setting::query()->where('key', $key)->exists();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (! $this->tableExists()) {
            return $default;
        }

        $row = Setting::query()->where('key', $key)->first();

        return $row !== null ? $row->value : $default;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        if (! $this->tableExists()) {
            return [];
        }

        return Setting::query()->pluck('value', 'key')->all();
    }

    public function set(string $key, mixed $value): void
    {
        if (! $this->tableExists()) {
            return;
        }

        $stored = is_string($value) ? $value : json_encode($value);

        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $stored]
        );
    }

    public function forget(string $key): void
    {
        if (! $this->tableExists()) {
            return;
        }

        Setting::query()->where('key', $key)->delete();
    }

    /**
     * When null, all adapters from config are eligible to boot.
     * When array (possibly empty), only these classes (must also appear in config) are booted.
     *
     * @return list<string>|null
     */
    public function getEnabledAdapterClasses(): ?array
    {
        if (! $this->has(self::KEY_ENABLED_ADAPTERS)) {
            return null;
        }

        $raw = $this->get(self::KEY_ENABLED_ADAPTERS);
        if ($raw === null || $raw === '') {
            return [];
        }

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);

            return is_array($decoded) ? array_values(array_filter($decoded, 'is_string')) : [];
        }

        return [];
    }

    /**
     * @param  list<string>  $classes
     */
    public function setEnabledAdapterClasses(array $classes): void
    {
        $this->set(self::KEY_ENABLED_ADAPTERS, array_values($classes));
    }

    protected function tableExists(): bool
    {
        return Schema::hasTable('atlas_settings');
    }
}
