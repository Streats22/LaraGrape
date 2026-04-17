@php
    $m = $this->canvasMode;
    $btn = 'rounded-md px-2.5 py-1.5 text-xs font-semibold transition';
    $on = $btn.' bg-slate-700 text-white shadow';
    $off = $btn.' text-slate-400 hover:text-slate-200';
@endphp

<div class="inline-flex max-w-full flex-wrap items-center gap-1 rounded-lg border border-slate-600/80 bg-slate-900/80 p-0.5" role="group" aria-label="Canvas layout mode">
    <button type="button" wire:click="setCanvasMode('studio')" class="{{ $m === 'studio' ? $on : $off }}">
        {{ config('atlas.ui.canvas.studio_label') }}
    </button>
    <button type="button" wire:click="setCanvasMode('preview')" class="{{ $m === 'preview' ? $on : $off }}">
        {{ config('atlas.ui.canvas.preview_label') }}
    </button>
    <button type="button" wire:click="setCanvasMode('creative')" class="{{ $m === 'creative' ? $on : $off }}">
        {{ config('atlas.ui.canvas.creative_label') }}
    </button>
</div>
