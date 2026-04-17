@php
    use Illuminate\Support\Str;
    use Streats\Atlas\Support\AtlasUi;
@endphp

<div id="atlas-live-page-editor" class="flex h-[100dvh] min-h-0 flex-col" wire:key="atlas-live-{{ $page->id }}">
    @if ($this->lastSavedBlockId)
        <div wire:poll.3s="clearSavePulse" class="sr-only" aria-hidden="true"></div>
    @endif
    <header class="z-50 flex shrink-0 flex-wrap items-center justify-between gap-3 border-b border-slate-700/50 bg-slate-900 px-4 py-3 shadow-lg">
        <div class="flex min-w-0 flex-wrap items-center gap-3">
            <span class="rounded-full bg-amber-500/15 px-2.5 py-1 text-[0.65rem] font-bold uppercase tracking-wider text-amber-300">
                Editor active
            </span>
            <div class="min-w-0">
                <h1 class="truncate text-sm font-semibold text-white">{{ $page->title }}</h1>
                <p class="font-mono text-[0.65rem] text-slate-500">/{{ $page->slug }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                wire:click="togglePalette"
                class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-medium text-slate-200 hover:bg-slate-800 lg:hidden"
            >
                {{ $this->paletteOpen ? 'Hide' : 'Show' }} widgets
            </button>
            <button
                type="button"
                wire:click="togglePalette"
                class="hidden rounded-lg border border-slate-600 px-3 py-2 text-xs font-medium text-slate-200 hover:bg-slate-800 lg:inline-block"
                title="Toggle widget library"
            >
                Widgets
            </button>
            @if ($previewUrl)
                <a
                    href="{{ $previewUrl }}"
                    class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800"
                    target="_blank"
                    rel="noopener"
                >
                    Preview
                </a>
            @endif
            <a
                href="{{ $dashboardUrl }}"
                class="rounded-lg bg-slate-800 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700"
            >
                Exit
            </a>
        </div>
    </header>

    <div class="border-b border-slate-700/50 bg-slate-900/95 px-4 py-2">
        @include('atlas::livewire.partials.canvas-mode-switch')
    </div>

    <div class="flex min-h-0 flex-1 flex-col lg:flex-row">
        <div class="relative min-h-0 min-w-0 flex-1 overflow-y-auto bg-slate-200 dark:bg-slate-900">
            <div class="mx-auto max-w-3xl px-4 py-8">
                @if (count($blockTypes) === 0)
                    <p class="rounded-lg border border-red-500/40 bg-red-950/30 p-4 text-sm text-red-200">
                        No block types registered. Call <code class="rounded bg-slate-800 px-1 font-mono text-xs">Atlas::registerDefaultBlocks()</code>.
                    </p>
                @endif

                @include('atlas::livewire.partials.editable-canvas-stack', [
                    'skin' => 'live',
                    'sortableId' => 'atlas-live-sortable-blocks',
                    'dropZoneId' => 'atlas-live-canvas-drop-zone',
                    'dropLineId' => 'atlas-live-drop-line',
                    'page' => $page,
                    'blockPreviews' => $blockPreviews,
                    'blockTypes' => $blockTypes,
                    'wireKeyPrefix' => 'live-blk',
                    'stackClass' => 'flex min-h-[50vh] flex-col gap-4',
                    'emptyBoxClass' => 'flex min-h-[12rem] flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-400 bg-white/80 px-6 text-center dark:border-slate-600 dark:bg-slate-950/40',
                ])
            </div>
        </div>

        <aside
            class="{{ AtlasUi::widgetLibrary('aside', 'shrink-0 border-t border-slate-700/50 bg-slate-950 p-4 transition-all duration-200 dark:border-slate-700 lg:border-l lg:border-t-0') }} {{ $this->paletteOpen ? 'max-lg:max-h-[40vh] max-lg:overflow-y-auto lg:w-80' : 'max-lg:hidden lg:w-0 lg:overflow-hidden lg:border-0 lg:p-0' }}"
        >
            <p class="{{ AtlasUi::widgetLibrary('title', 'text-[0.65rem] font-bold uppercase tracking-widest text-slate-500') }}">Widgets</p>
            <p class="{{ AtlasUi::widgetLibrary('help', 'mt-1 text-xs text-slate-500') }}">Drag onto the page or double-click to append.</p>

            <div wire:ignore class="{{ AtlasUi::widgetLibrary('list', 'mt-4') }}">
                <div id="atlas-live-block-palette" class="space-y-2">
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
        'builderRootId' => 'atlas-live-page-editor',
        'dropZoneId' => 'atlas-live-canvas-drop-zone',
        'sortableId' => 'atlas-live-sortable-blocks',
        'paletteId' => 'atlas-live-block-palette',
        'dropLineId' => 'atlas-live-drop-line',
    ])
@endscript
