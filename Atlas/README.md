# Atlas Builder

Laravel-native page builder core: blocks, rendering, caching, adapters, and optional Livewire admin UI.

## Requirements

- PHP 8.2+
- Laravel 11 / 12 / 13 (`illuminate/*` ^11|^12|^13)
- Livewire 3 (for `SetupWizard` and `PageBuilder` components)

## Install

```bash
composer require streatsdesign/atlas-builder
php artisan migrate
```

Optional publish:

```bash
php artisan atlas:install
```

Register block classes in `AppServiceProvider`:

```php
use Streats\Atlas\Facades\Atlas;
use App\Blocks\HeroBlock;

public function boot(): void
{
    Atlas::registerBlock(HeroBlock::class);
}
```

## Documentation

- **[ARCHITECTURE.md](ARCHITECTURE.md)** — maps each architecture spec section to source files and explains adapters, settings, and preview.

## Optional package routes

Set in `config/atlas.php` (`routes.enabled` / `routes.prefix`) to expose setup wizard, page builder, and signed preview URLs. Defaults are off; enable in local apps only as needed.
