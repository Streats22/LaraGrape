@php
    use Illuminate\Support\Str;
    use Streats\Atlas\Support\AtlasUi;
@endphp

<div id="atlas-page-builder" class="flex min-h-screen flex-col" wire:key="atlas-builder-{{ $page->id }}">
    @if ($this->lastSavedBlockId)
        <div wire:poll.3s="clearSavePulse" class="sr-only" aria-hidden="true"></div>
    @endif
    <header class="sticky top-0 z-40 flex flex-col gap-3 border-b border-slate-700/50 bg-slate-900/95 px-5 py-3 backdrop-blur sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
        <div>
            <h1 class="text-base font-semibold tracking-tight text-white">{{ $page->title }}</h1>
            <p class="font-mono text-xs text-slate-400">/{{ $page->slug }} · {{ $page->blocks->count() }} blocks</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
            @include('atlas::livewire.partials.canvas-mode-switch')
        <div class="flex flex-wrap items-center gap-2">
            @if (\Illuminate\Support\Facades\Route::has('atlas.dashboard'))
                <a
                    href="{{ route('atlas.dashboard') }}"
                    class="rounded-lg border border-slate-600 bg-transparent px-3 py-2 text-sm font-medium text-slate-200 hover:bg-slate-800"
                >
                    Dashboard
                </a>
            @endif
            <a
                href="{{ url('/') }}"
                class="rounded-lg border border-slate-600 bg-transparent px-3 py-2 text-sm font-medium text-slate-200 hover:bg-slate-800"
                wire:navigate
            >
                Home
            </a>
            @if ($previewUrl)
                <a
                    href="{{ $previewUrl }}"
                    class="rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-lg shadow-sky-500/25 hover:brightness-110"
                    target="_blank"
                    rel="noopener"
                >
                    Preview
                </a>
            @endif
        </div>
        </div>
    </header>

    <div class="flex min-h-0 flex-1 flex-col lg:flex-row">
        {{-- Canvas (Elementor-style: main preview area) --}}
        <div class="min-h-0 min-w-0 flex-1 overflow-y-auto border-slate-700/50 p-4 lg:border-r">
            <div class="mb-4">
                <p class="text-[0.65rem] font-bold uppercase tracking-widest text-slate-500">Page</p>
                <p class="text-xs text-slate-400">Drag widgets from the library into this area, or double-click a widget to add at the end. Drag the grip on a row to reorder.</p>
            </div>

            @if (count($blockTypes) === 0)
                <p class="rounded-lg border border-red-500/30 bg-red-950/20 p-4 text-sm text-red-300">
                    No block types registered. Call <code class="rounded bg-slate-800 px-1 font-mono text-xs">Atlas::registerDefaultBlocks()</code> or <code class="rounded bg-slate-800 px-1 font-mono text-xs">Atlas::registerBlock()</code>.
                </p>
            @endif

            @include('atlas::livewire.partials.editable-canvas-stack', [
                'skin' => 'builder',
                'sortableId' => 'atlas-sortable-blocks',
                'dropZoneId' => 'atlas-canvas-drop-zone',
                'dropLineId' => 'atlas-drop-line',
                'page' => $page,
                'blockPreviews' => $blockPreviews,
                'blockTypes' => $blockTypes,
                'wireKeyPrefix' => 'blk',
                'stackClass' => 'flex min-h-[min(70vh,32rem)] flex-col gap-3',
                'emptyBoxClass' => 'flex min-h-[14rem] flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-600 bg-slate-900/40 px-6 text-center',
            ])
        </div>

        {{-- Widget library (Elementor-style right panel) --}}
        <aside class="{{ AtlasUi::widgetLibrary('aside', 'w-full shrink-0 border-t border-slate-700/50 bg-slate-950/80 p-4 lg:w-80 lg:border-l lg:border-t-0') }}">
            <p class="{{ AtlasUi::widgetLibrary('title', 'text-[0.65rem] font-bold uppercase tracking-widest text-slate-500') }}">Widgets</p>
            <p class="{{ AtlasUi::widgetLibrary('help', 'mt-1 text-xs leading-relaxed text-slate-500') }}">Drag onto the page, or double-click to append.</p>

            <div wire:ignore class="{{ AtlasUi::widgetLibrary('list', 'mt-4') }}">
                <div id="atlas-block-palette" class="space-y-2">
                    @foreach ($blockTypes as $t)
                        <div
                            data-atlas-palette-type="{{ $t }}"
                            draggable="true"
                            class="{{ AtlasUi::widgetLibrary('item', 'atlas-palette-item flex cursor-grab select-none gap-3 rounded-xl border border-slate-600 bg-slate-800/90 px-3 py-3 shadow transition hover:border-sky-500/40 hover:bg-slate-800 active:cursor-grabbing') }}"
                        >
                            <span class="flex shrink-0 items-center text-slate-500" aria-hidden="true">
                                <span class="text-lg leading-none">⋮⋮</span>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white">{{ Str::title(str_replace('_', ' ', $t)) }}</p>
                                <p class="truncate font-mono text-[0.65rem] text-slate-500">{{ $t }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</div>

@script
    @include('atlas::livewire.partials.builder-dnd', [
        'builderRootId' => 'atlas-page-builder',
        'dropZoneId' => 'atlas-canvas-drop-zone',
        'sortableId' => 'atlas-sortable-blocks',
        'paletteId' => 'atlas-block-palette',
        'dropLineId' => 'atlas-drop-line',
    ])
@endscript
