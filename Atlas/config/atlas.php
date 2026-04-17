<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Adapter classes
    |--------------------------------------------------------------------------
    |
    | Each adapter receives boot() when Atlas starts. Keep adapters isolated
    | from core block logic (Nova/Filament/Dashboard registrations, etc.).
    |
    */
    'adapters' => [
        // \App\Builder\Adapters\DashboardAdapter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | View namespace for block templates
    |--------------------------------------------------------------------------
    |
    | Views resolve as: {namespace}.{type}.{style}
    | Default namespace is "atlas::blocks" → resources/views/vendor/atlas/blocks/...
    |
    */
    'view_namespace' => 'atlas::blocks',

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (seconds) for CachedRenderer
    |--------------------------------------------------------------------------
    */
    'cache_ttl' => 3600,

    /*
    |--------------------------------------------------------------------------
    | Published block HTML (front + preview)
    |--------------------------------------------------------------------------
    |
    | wrap_blocks: wrap each block in a <section class="atlas-block ..."> for
    | layout hooks and clearer DOM. wrapper_extra_class: appended classes.
    |
    */
    'rendering' => [
        'wrap_blocks' => true,
        'wrapper_extra_class' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Optional HTTP routes (setup wizard, builder, signed preview)
    |--------------------------------------------------------------------------
    |
    | Keep disabled in packages used only as a library; enable in your app
    | when you want /atlas/* endpoints without wiring routes yourself.
    |
    */
    'routes' => [
        'enabled' => false,
        'prefix' => 'atlas',
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Minimal pages dashboard (no Filament / Nova)
    |--------------------------------------------------------------------------
    |
    | When enabled, a Livewire dashboard lists Atlas pages. If
    | show_when_no_filament_or_nova is true, the route is only registered when
    | neither Filament nor Nova is installed, so you can use your own admin
    | without a duplicate entry point.
    |
    */
    'dashboard' => [
        'enabled' => true,
        'show_when_no_filament_or_nova' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Live page editor (/atlas/pages/{page}/live)
    |--------------------------------------------------------------------------
    |
    | Middleware stack for the dedicated frontend editor route. Use ['web',
    | 'auth'] (or a permission middleware) in production apps that require a
    | logged-in editor; the playground defaults to web only.
    |
    */
    'frontend_editor' => [
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | UI (widget library + editor copy)
    |--------------------------------------------------------------------------
    |
    | widget_library: append Tailwind (or any) classes to the default panel.
    | Keys are merged after the package defaults — publish this file in your
    | app and set e.g. 'aside' => 'bg-zinc-950 border-violet-500/30'.
    |
    | editor: labels for save actions (override for Dutch or client wording).
    |
    */
    'ui' => [
        'widget_library' => [
            'aside' => '',
            'title' => '',
            'help' => '',
            'list' => '',
            'item' => '',
        ],
        'editor' => [
            'save_button_label' => 'Save changes',
            'saving_label' => 'Saving…',
            'saved_banner' => 'All changes have been saved to the database.',
            'saved_chip' => 'Saved',
        ],
        /*
        | Canvas presentation in page builder & live editor (session-persisted).
        | studio: structured panels (default). preview: front-like surface, slim chrome.
        | creative: looser layout, gradients, more whitespace.
        */
        'canvas' => [
            'default_mode' => 'studio',
            'studio_label' => 'Studio',
            'preview_label' => 'Preview',
            'creative_label' => 'Creative',
        ],
    ],

];
