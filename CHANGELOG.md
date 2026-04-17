# Changelog

All notable changes to StreatsDesign / LaraGrape are documented in this file.

---

## [Unreleased] – Branch refactor (vs main)

### 🔄 Breaking Changes – Architecture Migration

The project has been restructured from a **single Laravel application** (LaraGrape boilerplate) into a **monorepo with a reusable package**:

- **`packages/streats22/LaraGrape`** – Extracted LaraGrape package (Laravel + GrapesJS + Filament)
- **`streatsdesign`** – Main production application consuming the package
- **`test-laragrape`** – Local-only test app (not included in distribution; for development/QA)

### 📦 Removed from Root (moved to package or apps)

#### Configuration & tooling
- `.editorconfig` – Moved to package/apps
- `.env.example` – Moved to package/apps
- `.gitattributes` – Moved to package/apps
- `.gitignore` – Moved to package/apps
- `composer.json` / `composer.lock` – Replaced by per-app configs
- `package.json` / `package-lock.json` – Replaced by per-app configs
- `phpunit.xml` – Moved to apps
- `tailwind.config.js` – Moved to apps
- `vite.config.js` – Moved to apps

#### Documentation
- `README.md` – LaraGrape boilerplate readme (moved to package)
- `BLOCKS_README.md` – Block system docs (moved to package)
- `COMPONENTS_README.md` – Component system docs (moved to package)
- `CUSTOM_BLOCKS_README.md` – Custom block builder docs (moved to package)
- `LARALGRAPE_SETUP.md` – Setup guide (moved to package)

#### Application core
- `app/` – Entire application directory (models, controllers, services, Filament resources)
- `artisan` – Moved to apps
- `bootstrap/` – Moved to apps
- `config/` – Moved to apps
- `database/` – Migrations, seeders, factories (moved to package + apps)
- `public/` – Public assets (moved to apps)
- `resources/` – Views, CSS, JS (moved to package + apps)
- `routes/` – Web and console routes (moved to package + apps)
- `tests/` – PHPUnit tests (moved to apps)

#### App components
- `app/Console/Commands/RebuildTailwindCommand.php`
- `app/Console/Kernel.php`
- `app/Filament/Forms/Components/GrapesJsEditor.php`
- `app/Filament/Pages/Dashboard.php`
- `app/Filament/Resources/*` (CustomBlock, Page, SiteSettings, TailwindConfig)
- `app/Http/Controllers/*` (AdminPageController, PageController)
- `app/Models/*` (CustomBlock, Page, SiteSettings, TailwindConfig, User)
- `app/Providers/*`
- `app/Services/*` (BlockService, GrapesJsConverterService, SiteSettingsService)

#### Views
- `resources/views/components/layout/*` (app, header, footer, grapejs-edit-bar)
- `resources/views/components/blocks/*`
- `resources/views/components/forms/form-block.blade.php`
- `resources/views/filament/blocks/*` (layouts, content, media, forms, components)
- `resources/views/filament/forms/components/grapesjs-editor.blade.php`
- `resources/views/filament/components/*`
- `resources/views/pages/show.blade.php`
- `resources/views/welcome.blade.php`

#### Assets
- `resources/css/*` (app.css, site.css, filament-grapesjs-editor.css, etc.)
- `resources/js/*` (app.js, bootstrap.js, grapesjs-editor.js)
- `public/css/*` – Compiled Filament and LaraGrape styles
- `public/js/*` – Compiled Filament JS

### ➕ Added

#### New structure
- **`packages/streats22/LaraGrape/`** – Composable Laravel package
  - `src/` – Package source (Services, Models, Controllers, Filament, etc.)
  - `resources/views/` – Block views, layouts, Filament components
  - `resources/css/` – Site and editor styles
  - `database/migrations/` – Package migrations
  - `routes/` – Package routes

- **`streatsdesign/`** – Main Laravel app
  - Consumes `streats22/laragrape` via Composer path
  - Custom branding and configuration
  - Published and customized views

- **`test-laragrape/`** – Local test app (not included; create locally via setup script)
- **`test-laragrape-setup.sh`** – Script to create and configure test app locally

#### Package features (preserved from main)
- Dynamic block system
- GrapesJS visual editor
- Filament admin panel
- Custom block builder
- Site settings management
- Tailwind config management
- Blade content conversion with `data-laragrape-block` for animated blocks

---

## Statistics

- **165 files** removed from root (moved or restructured)
- **~49,500 lines** removed (relocated to package/apps)
