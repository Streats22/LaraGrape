@props([
    'skin' => 'builder',
    'sortableId',
    'dropZoneId',
    'dropLineId',
    'page',
    'blockPreviews',
    'blockTypes',
    'wireKeyPrefix' => 'blk',
    'stackClass' => 'flex min-h-[min(70vh,32rem)] flex-col gap-3',
    'emptyBoxClass' => 'flex min-h-[14rem] flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-600 bg-slate-900/40 px-6 text-center',
])

<div id="{{ $dropZoneId }}" class="relative rounded-xl border border-dashed border-transparent transition-colors">
    <div
        id="{{ $dropLineId }}"
        class="pointer-events-none absolute left-0 right-0 z-20 h-0.5 rounded-full bg-sky-500 opacity-0 shadow-[0_0_12px_rgba(56,189,248,0.8)] transition-opacity duration-75"
        style="display: none; top: 0"
        aria-hidden="true"
    ></div>
    <div id="{{ $sortableId }}" class="{{ $stackClass }}">
        @forelse ($page->blocks as $block)
            @include('atlas::livewire.partials.editable-block-row', [
                'block' => $block,
                'blockPreviews' => $blockPreviews,
                'skin' => $skin,
                'wireKeyPrefix' => $wireKeyPrefix,
            ])
        @empty
            @if (count($blockTypes) > 0)
                <div class="{{ $emptyBoxClass }}">
                    <p class="text-sm font-medium text-slate-400">Drop widgets here</p>
                    <p class="mt-1 max-w-xs text-xs text-slate-500">Drag from the library{{ $skin === 'live' ? '' : ' on the right' }}, or double-click a widget to add.</p>
                </div>
            @endif
        @endforelse
    </div>
</div>
