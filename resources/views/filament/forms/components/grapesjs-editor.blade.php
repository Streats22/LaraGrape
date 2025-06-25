@php
    $id = $getId();
    $isDisabled = $isDisabled();
    $statePath = $getStatePath();
    $height = $getHeight();
    $state = $getState();
    
    // Load blocks dynamically from BlockService
    $blockService = app(\App\Services\BlockService::class);
    $blocks = $blockService->getGrapesJsBlocks();

    $appCss = Vite::asset('resources/css/app.css');
    $siteCss = Vite::asset('resources/css/site.css');
    $grapesCss = Vite::asset('resources/css/filament-grapesjs-editor.css');
    $adminCss = Vite::asset('resources/css/filament/admin/theme.css');
    
    // Try to get the record (could be a model or a slug)
    $record = null;
    $pageId = null;
    $saveUrl = null;
    $isCreate = true;

    try {
        $record = $getRecord();
    } catch (\Throwable $e) {
        $record = null;
    }

    // If $record is a model, get the ID
    if (is_object($record) && method_exists($record, 'getKey')) {
        $pageId = $record->getKey();
        $isCreate = false;
    }
    // If $record is a string (slug), look up the page
    elseif (is_string($record)) {
        $page = \App\Models\Page::where('slug', $record)->first();
        if ($page) {
            $pageId = $page->id;
            $isCreate = false;
        }
    }

    if ($pageId) {
        $saveUrl = route('admin.page.save-grapesjs', $pageId);
    }
    
    // Debug information
    \Log::info('GrapesJS Editor Debug', [
        'record_exists' => $record ? 'yes' : 'no',
        'record_id' => $pageId,
        'isCreate' => $isCreate,
        'saveUrl' => $saveUrl,
        'statePath' => $statePath,
        'url' => request()->url(),
        'route_name' => request()->route()->getName() ?? 'unknown',
        'route_parameters' => request()->route()->parameters() ?? []
    ]);
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="grapesjs-editor-wrapper" id="wrapper-{{ $id }}">
        <div class="grapesjs-controls">
            <button 
                type="button" 
                class="fullscreen-toggle-btn"
                title="Toggle Fullscreen Mode (Press Escape to exit)"
            >
                <svg class="fullscreen-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                </svg>
                <svg class="exit-fullscreen-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                    <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
                </svg>
            </button>
            <button type="button" class="grapesjs-save-btn" style="margin: 10px 0; padding: 8px 18px; background: #9333ea; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Save</button>
        </div>
        <div 
            id="{{ $id }}"
            class="grapesjs-editor"
            style="height: {{ $height }}; min-height: 400px; border: 1.5px solid #e5e7eb; background: #fff;"
            data-state-path="{{ $statePath }}"
            data-current-state="{{ json_encode($state) }}"
            data-height="{{ $height }}"
            data-disabled="{{ $isDisabled ? 'true' : 'false' }}"
            data-blocks="{{ json_encode($blocks) }}"
            data-page-id="{{ $pageId }}"
            data-save-url="{{ $saveUrl }}"
            data-is-create="{{ $isCreate ? 'true' : 'false' }}"
        >
        </div>
        {{ $getChildComponentContainer() }}
    </div>
    
    @push('scripts')
        <script type="module" src="{{ Vite::asset('resources/js/grapesjs-editor.js') }}"></script>
        <script>
            window.grapesjsCanvasStyles = [@json($appCss)];
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new window.LaraGrapeGrapesJsEditor({
                    containerId: '{{ $id }}',
                    mode: 'backend',
                    statePath: '{{ $statePath }}',
                    blocks: @json($blocks),
                    initialData: @json($state),
                    isDisabled: {{ $isDisabled ? 'true' : 'false' }},
                    height: '{{ $height }}'
                });
            });
        </script>
    @endpush
</x-dynamic-component>
