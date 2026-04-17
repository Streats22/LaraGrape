<?php

namespace Streats\Atlas\Services;

use Composer\InstalledVersions;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Fields\FieldRegistry;

/**
 * Read-only snapshot of the host application for the setup wizard and demo screens.
 */
class SystemDetection
{
    /**
     * @return array{
     *   php: string,
     *   laravel: string,
     *   environment: string,
     *   livewire: array{installed: bool, version: string|null},
     *   filament: array{installed: bool, version: string|null},
     *   nova: array{installed: bool, version: string|null},
     *   block_types: int,
     *   field_types: int,
     *   database_connection: string,
     *   database_driver: string|null,
     *   atlas_config_published: bool,
     *   atlas_views_published: bool,
     * }
     */
    public function snapshot(): array
    {
        $conn = config('database.default');
        $driver = config("database.connections.{$conn}.driver");

        $filamentVersion = $this->packageVersion('filament/filament');
        $novaVersion = $this->packageVersion('laravel/nova');
        $livewireVersion = $this->packageVersion('livewire/livewire');

        return [
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'environment' => app()->environment(),
            'livewire' => [
                'installed' => class_exists(\Livewire\Livewire::class) || $livewireVersion !== null,
                'version' => $livewireVersion,
            ],
            'filament' => [
                'installed' => $filamentVersion !== null,
                'version' => $filamentVersion,
            ],
            'nova' => [
                'installed' => $novaVersion !== null,
                'version' => $novaVersion,
            ],
            'block_types' => count(BlockRegistry::all()),
            'field_types' => count(FieldRegistry::all()),
            'database_connection' => is_string($conn) ? $conn : 'default',
            'database_driver' => is_string($driver) ? $driver : null,
            'atlas_config_published' => file_exists(config_path('atlas.php')),
            'atlas_views_published' => is_dir(resource_path('views/vendor/atlas')),
        ];
    }

    protected function packageVersion(string $packageName): ?string
    {
        if (! class_exists(InstalledVersions::class)) {
            return null;
        }

        if (! InstalledVersions::isInstalled($packageName)) {
            return null;
        }

        try {
            return InstalledVersions::getPrettyVersion($packageName);
        } catch (\Throwable) {
            return null;
        }
    }
}
