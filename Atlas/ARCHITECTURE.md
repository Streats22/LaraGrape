# Atlas Builder — spec to code map

This document maps the Advanced Architecture sections to package types and files. It is the canonical reference for maintainers.

## Section 1 — Block lifecycle

| Concept | Implementation |
|--------|------------------|
| Abstract block definition | [`src/Blocks/Block.php`](src/Blocks/Block.php) |
| Per-type registration | [`src/Blocks/BlockRegistry.php`](src/Blocks/BlockRegistry.php) |
| Example block | [`src/Blocks/HeadingBlock.php`](src/Blocks/HeadingBlock.php) |

`rules()`, `mutateBeforeSave()`, `mutateAfterLoad()` are implemented on `Block`.

## Section 2 — Block service layer

| Concept | Implementation |
|--------|------------------|
| Create / save / reorder | [`src/Services/BlockService.php`](src/Services/BlockService.php) |

`saveFields` validates with `rules()` before `mutateBeforeSave`, then persists field rows.

## Section 3 — DTO

| Concept | Implementation |
|--------|------------------|
| View-facing data | [`src/DTO/BlockData.php`](src/DTO/BlockData.php) |

## Section 4 — Rendering

| Concept | Implementation |
|--------|------------------|
| View name `atlas::blocks.{type}.{style}` | [`src/Services/Renderer.php`](src/Services/Renderer.php) |

## Section 5 — Caching

| Concept | Implementation |
|--------|------------------|
| Cached render | [`src/Services/CachedRenderer.php`](src/Services/CachedRenderer.php) |
| Cache key / TTL | [`src/Services/PageRenderCache.php`](src/Services/PageRenderCache.php) |
| Invalidation | [`src/Observers/*Observer.php`](src/Observers) |

## Section 6 — Drag and drop

| Concept | Implementation |
|--------|------------------|
| Reorder API | [`src/Services/BlockService.php`](src/Services/BlockService.php) `reorder()` |
| Livewire trait | [`src/Livewire/Concerns/ReordersBlocks.php`](src/Livewire/Concerns/ReordersBlocks.php) |
| Full UI | [`src/Livewire/PageBuilder.php`](src/Livewire/PageBuilder.php) (SortableJS) |

## Section 7 — Field mapping

| Concept | Implementation |
|--------|------------------|
| Portable descriptors | [`src/Fields/TextField.php`](src/Fields/TextField.php), [`src/Fields/MediaField.php`](src/Fields/MediaField.php) |
| Mapper | [`src/FieldMapper.php`](src/FieldMapper.php) |
| Nova / Filament bridge | [`src/Adapters/Nova/FieldDescriptorToNova.php`](src/Adapters/Nova/FieldDescriptorToNova.php), [`src/Adapters/Filament/FieldDescriptorToFilament.php`](src/Adapters/Filament/FieldDescriptorToFilament.php) |

Descriptors are arrays or objects with `toNova()` / `toFilament()` so core does not depend on Nova/Filament packages.

## Section 8 — Adapters

| Concept | Implementation |
|--------|------------------|
| Config list | [`config/atlas.php`](config/atlas.php) `adapters` |
| Boot | [`src/Adapters/AdapterManager.php`](src/Adapters/AdapterManager.php) |
| Contract | [`src/Contracts/AtlasAdapter.php`](src/Contracts/AtlasAdapter.php) |

DB-filtered enablement uses [`src/Repositories/SettingsRepository.php`](src/Repositories/SettingsRepository.php) key `atlas.enabled_adapters`.

## Section 9 — Setup wizard

| Concept | Implementation |
|--------|------------------|
| Settings storage | [`src/Models/Setting.php`](src/Models/Setting.php), `atlas_settings` table |
| Repository | [`src/Repositories/SettingsRepository.php`](src/Repositories/SettingsRepository.php) |
| Livewire UI | [`src/Livewire/SetupWizard.php`](src/Livewire/SetupWizard.php) |

## Section 10 — Plugin API

| Concept | Implementation |
|--------|------------------|
| Facade | [`src/Facades/Atlas.php`](src/Facades/Atlas.php) |
| Manager | [`src/AtlasManager.php`](src/AtlasManager.php) |

## Section 11 — Nested blocks

Not implemented: no `parent_id` on `atlas_blocks`.

## Section 13 — Security

Validation runs in `BlockService::saveFields` via `Block::rules()`. HTML sanitization and uploads are application concerns; use `mutateBeforeSave` and Blade escaping (`{{ }}`).

## Section 14 — Testing

Tests live under [`tests/`](tests/). Add feature tests for new surfaces (adapters, preview) as behavior grows.

## Section 15 — CLI

| Command | Class |
|--------|--------|
| `atlas:install` | [`src/Console/InstallCommand.php`](src/Console/InstallCommand.php) |
| `atlas:cache` | [`src/Console/CacheCommand.php`](src/Console/CacheCommand.php) |
| `atlas:clear` | [`src/Console/ClearCommand.php`](src/Console/ClearCommand.php) |

## Revisions and preview (extended)

| Concept | Implementation |
|--------|------------------|
| Revisions table | `atlas_page_revisions` migration |
| Model | [`src/Models/PageRevision.php`](src/Models/PageRevision.php) |
| Snapshots | [`src/Services/RevisionService.php`](src/Services/RevisionService.php) |
| Signed preview | [`src/Http/Controllers/PagePreviewController.php`](src/Http/Controllers/PagePreviewController.php), [`src/Services/PagePreviewService.php`](src/Services/PagePreviewService.php) |
