<?php

namespace Streats\Atlas;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Streats\Atlas\Adapters\AdapterManager;
use Streats\Atlas\Console\CacheCommand;
use Streats\Atlas\Console\ClearCommand;
use Streats\Atlas\Console\InstallCommand;
use Streats\Atlas\Fields\FieldRegistry;
use Streats\Atlas\Fields\TextareaField;
use Streats\Atlas\Fields\TextField;
use Streats\Atlas\Models\BlockField;
use Streats\Atlas\Models\BlockModel;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Observers\BlockFieldObserver;
use Streats\Atlas\Observers\BlockModelObserver;
use Streats\Atlas\Observers\PageObserver;
use Streats\Atlas\Repositories\SettingsRepository;
use Streats\Atlas\Services\CachedRenderer;
use Streats\Atlas\Services\PagePreviewService;
use Streats\Atlas\Services\Renderer;
use Streats\Atlas\Services\RevisionService;
use Streats\Atlas\Services\SystemDetection;

class AtlasServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/atlas.php', 'atlas');

        $this->app->singleton(AtlasManager::class);
        $this->app->alias(AtlasManager::class, 'atlas');

        $this->app->singleton(SettingsRepository::class);

        $this->app->singleton(Renderer::class);
        $this->app->singleton(CachedRenderer::class);

        $this->app->singleton(PagePreviewService::class);
        $this->app->singleton(RevisionService::class);
        $this->app->singleton(SystemDetection::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/atlas.php' => config_path('atlas.php'),
        ], 'atlas-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'atlas-migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/atlas'),
        ], 'atlas-views');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        View::addNamespace('atlas', __DIR__.'/../resources/views');

        BlockModel::observe(BlockModelObserver::class);
        BlockField::observe(BlockFieldObserver::class);
        Page::observe(PageObserver::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CacheCommand::class,
                ClearCommand::class,
            ]);
        }

        FieldRegistry::register(TextField::class);
        FieldRegistry::register(TextareaField::class);
        FieldRegistry::register(\Streats\Atlas\Fields\MediaField::class);

        $this->registerLivewireComponents();

        if (config('atlas.routes.enabled', false)) {
            Route::middleware(config('atlas.routes.middleware', ['web']))
                ->prefix(config('atlas.routes.prefix', 'atlas'))
                ->group(__DIR__.'/../routes/atlas.php');
        }

        app(AdapterManager::class)->boot();
    }

    protected function registerLivewireComponents(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        Livewire::component('atlas.setup-wizard', \Streats\Atlas\Livewire\SetupWizard::class);
        Livewire::component('atlas.page-builder', \Streats\Atlas\Livewire\PageBuilder::class);
        Livewire::component('atlas.atlas-dashboard', \Streats\Atlas\Livewire\AtlasDashboard::class);
        Livewire::component('atlas.live-page-editor', \Streats\Atlas\Livewire\LivePageEditor::class);
    }
}
