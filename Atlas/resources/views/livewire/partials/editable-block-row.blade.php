@props(['block', 'blockPreviews', 'skin' => 'builder', 'wireKeyPrefix' => 'blk'])

@php
    $mode = $this->canvasMode;
    $isSelected = $this->selectedBlockId === $block->id;

    $rowShell = match ($mode) {
        'studio' => $skin === 'live'
            ? 'atlas-editor-block-root overflow-hidden rounded-xl border bg-white shadow transition-colors dark:border-slate-700 dark:bg-slate-950 '.($isSelected ? 'border-sky-500 ring-2 ring-sky-500/30 dark:border-sky-500' : 'border-slate-300 dark:border-slate-700')
            : 'overflow-hidden rounded-xl border transition-colors '.($isSelected ? 'border-sky-500/60 ring-1 ring-sky-500/30' : 'border-slate-700/60').' bg-slate-900/80 shadow-lg shadow-black/20',
        'preview' => 'overflow-hidden rounded-lg border bg-white text-slate-900 shadow-md transition-colors ring-1 ring-slate-900/5 '.($isSelected ? 'border-sky-500 ring-2 ring-sky-400/25' : 'border-slate-200'),
        'creative' => 'overflow-hidden rounded-2xl border-2 border-dashed bg-slate-900/55 shadow-2xl transition-all '.($isSelected ? 'border-sky-400/50 from-slate-900 ring-2 ring-violet-500/30' : 'border-sky-500/15'),
        default => '',
    };

    $toolbar = match ($mode) {
        'studio' => $skin === 'live'
            ? 'flex flex-wrap items-center gap-2 border-b border-slate-200 bg-slate-50 px-3 py-2 dark:border-slate-700 dark:bg-slate-900/90'
            : 'flex flex-wrap items-center gap-2 border-b border-slate-700/50 bg-slate-800/90 px-3 py-2',
        'preview' => 'flex flex-wrap items-center gap-2 border-b border-slate-200 bg-slate-50/95 px-2 py-1.5 text-xs text-slate-700',
        'creative' => 'flex flex-wrap items-center gap-2 border-b border-sky-500/20 bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950/40 px-3 py-2',
        default => '',
    };

    $previewSurface = match ($mode) {
        'studio' => $skin === 'live'
            ? 'cursor-pointer px-4 py-6 text-slate-900 dark:text-slate-100'
            : 'min-h-[3rem] cursor-pointer bg-slate-50 p-4 text-slate-900 dark:bg-slate-950 dark:text-slate-100',
        'preview' => 'atlas-editor-preview-surface cursor-pointer bg-white px-6 py-10 text-slate-900 shadow-inner dark:bg-white dark:text-slate-900',
        'creative' => 'cursor-pointer rounded-b-2xl bg-gradient-to-br from-white via-slate-50 to-sky-50/50 px-8 py-10 text-slate-900 shadow-inner dark:from-slate-900 dark:via-slate-950 dark:to-indigo-950/40 dark:text-slate-100',
        default => '',
    };

    $grip = match ($mode) {
        'studio' => $skin === 'live'
            ? 'atlas-row-handle cursor-grab select-none rounded px-1 text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800'
            : 'atlas-row-handle cursor-grab select-none rounded px-1.5 py-0.5 text-slate-400 hover:bg-slate-700 active:cursor-grabbing',
        'preview' => 'atlas-row-handle cursor-grab select-none rounded px-1 text-slate-400 hover:bg-slate-200',
        'creative' => 'atlas-row-handle cursor-grab select-none rounded-full border border-sky-500/30 bg-slate-800/80 px-2 py-1 text-sky-300 hover:bg-slate-800',
        default => '',
    };

    $typeChip = match ($mode) {
        'preview' => 'text-[0.65rem] font-bold uppercase tracking-wider text-slate-500',
        'creative' => 'text-[0.65rem] font-bold uppercase tracking-wider text-sky-300',
        default => $skin === 'live'
            ? 'text-[0.65rem] font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400'
            : 'text-[0.65rem] font-bold uppercase tracking-wider text-sky-400',
    };

    $btnBase = match ($mode) {
        'preview' => 'rounded-md border border-slate-300 px-2 py-1 text-[0.65rem] font-medium text-slate-700 hover:bg-slate-100',
        'creative' => 'rounded-lg border border-sky-500/30 bg-slate-900/60 px-2 py-1 text-[0.65rem] font-medium text-slate-200 hover:bg-slate-800',
        default => $skin === 'live'
            ? 'rounded-md border border-slate-300 px-2 py-1 text-[0.65rem] font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800'
            : 'rounded-md border border-slate-600 px-2 py-1 text-[0.65rem] font-medium text-slate-200 hover:bg-slate-700',
    };

    $delBtn = match ($mode) {
        'preview' => 'rounded-md border border-red-200 px-2 py-1 text-[0.65rem] font-medium text-red-700 hover:bg-red-50',
        'creative' => 'rounded-lg border border-red-500/40 px-2 py-1 text-[0.65rem] font-medium text-red-300 hover:bg-red-950/40',
        default => $skin === 'live'
            ? 'rounded-md border border-red-300 px-2 py-1 text-[0.65rem] font-medium text-red-700 hover:bg-red-50 dark:border-red-500/40 dark:text-red-300 dark:hover:bg-red-950/40'
            : 'rounded-md border border-red-500/40 px-2 py-1 text-[0.65rem] font-medium text-red-300 hover:bg-red-950/50',
    };
@endphp

<div
    wire:key="{{ $wireKeyPrefix }}-{{ $block->id }}"
    data-id="{{ $block->id }}"
    @if ($skin === 'live')
        data-atlas-block-id="{{ $block->id }}"
        data-atlas-type="{{ $block->type }}"
    @endif
    class="{{ $rowShell }}"
>
    <header class="{{ $toolbar }}" x-on:click.stop>
        <span class="{{ $grip }}" title="Drag to reorder">
            <span class="text-lg leading-none">⋮⋮</span>
        </span>
        <span class="{{ $typeChip }}">{{ str_replace('_', ' ', $block->type) }}</span>
        <span class="text-[0.65rem] text-slate-500">{{ $block->style }}</span>
        @if ($this->lastSavedBlockId === $block->id)
            <span
                class="inline-flex items-center gap-1 rounded-full bg-emerald-500/20 px-2 py-0.5 text-[0.65rem] font-semibold text-emerald-600 dark:text-emerald-400"
                wire:key="saved-chip-{{ $wireKeyPrefix }}-{{ $block->id }}"
            >
                <span aria-hidden="true">✓</span>{{ config('atlas.ui.editor.saved_chip') }}
            </span>
        @endif
        <div class="flex w-full flex-wrap gap-1 sm:ml-auto sm:w-auto">
            <button type="button" class="{{ $btnBase }}" wire:click.stop="selectBlock({{ $block->id }})">Edit</button>
            <button type="button" class="{{ $btnBase }}" wire:click.stop="duplicateBlock({{ $block->id }})">Duplicate</button>
            <button type="button" class="{{ $delBtn }}" wire:click.stop="deleteBlock({{ $block->id }})" wire:confirm="Delete this block?">Delete</button>
        </div>
    </header>

    @if ($isSelected)
        @include('atlas::livewire.partials.block-inspector', ['block' => $block, 'skin' => $skin])
    @endif

    <div class="{{ $previewSurface }}" wire:click="selectBlock({{ $block->id }})">
        {!! $blockPreviews[$block->id] ?? '' !!}
    </div>
</div>
