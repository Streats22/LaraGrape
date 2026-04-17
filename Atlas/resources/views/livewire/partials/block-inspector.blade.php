@props(['block', 'skin' => 'builder'])

@php
    $live = $skin;
    $isLive = $live === 'live';
    $styleFieldId = $isLive ? 'live-style-'.$block->id : 'canvas-style-'.$block->id;
@endphp

<div class="{{ $isLive ? 'space-y-3 border-b border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900/95' : 'space-y-3 border-b border-slate-700/50 bg-slate-900/95 p-4' }}" x-on:click.stop>
    @if ($this->fieldSaveBanner)
        <div
            class="{{ $isLive ? 'flex items-start gap-3 rounded-lg border border-emerald-500/40 bg-emerald-50 px-4 py-3 text-sm text-emerald-950 shadow-sm dark:border-emerald-500/40 dark:bg-emerald-950/50 dark:text-emerald-100' : 'flex items-start gap-3 rounded-lg border border-emerald-500/40 bg-emerald-950/50 px-4 py-3 text-sm text-emerald-100 shadow-sm' }}"
            role="status"
            wire:key="inspector-banner-{{ $block->id }}-{{ $live }}"
        >
            <span class="{{ $isLive ? 'mt-0.5 text-emerald-600 dark:text-emerald-400' : 'mt-0.5 text-emerald-400' }}" aria-hidden="true">✓</span>
            <span class="leading-snug">{{ config('atlas.ui.editor.saved_banner') }}</span>
        </div>
    @endif

    <fieldset class="space-y-3 rounded-lg border border-slate-600/50 p-3 {{ $isLive ? 'border-slate-300 bg-white/80 dark:border-slate-600 dark:bg-slate-800/50' : 'bg-slate-800/40' }}">
        <legend class="px-1 text-[0.65rem] font-bold uppercase tracking-wider text-slate-500">Block</legend>
        <div>
            <label class="mb-1 block text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500" for="{{ $styleFieldId }}">Style</label>
            <select
                id="{{ $styleFieldId }}"
                wire:change="applyStyle($event.target.value)"
                class="{{ $isLive ? 'w-full rounded-lg border border-slate-300 bg-white px-2 py-2 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100' : 'w-full rounded-lg border border-slate-600 bg-slate-800 px-2 py-2 text-sm text-slate-100' }}"
            >
                @foreach ($this->stylesForBlock($block) as $style)
                    <option value="{{ $style }}" @selected($this->inspectorStyle === $style)>{{ $style }}</option>
                @endforeach
            </select>
        </div>
    </fieldset>

    <fieldset class="space-y-3 rounded-lg border border-slate-600/50 p-3 {{ $isLive ? 'border-slate-300 bg-white/80 dark:border-slate-600 dark:bg-slate-800/50' : 'bg-slate-800/40' }}">
        <legend class="px-1 text-[0.65rem] font-bold uppercase tracking-wider text-slate-500">Content</legend>
        @foreach ($this->schemaForBlock($block) as $field)
            @if ($field['type'] === 'textarea')
                @include('atlas::livewire.partials.field-textarea', ['field' => $field])
            @elseif ($field['type'] === 'media')
                @include('atlas::livewire.partials.field-media', ['field' => $field])
            @else
                @include('atlas::livewire.partials.field-text', ['field' => $field])
            @endif
        @endforeach
    </fieldset>

    <div class="flex flex-wrap items-center gap-3 border-t border-slate-600/30 pt-3 {{ $isLive ? 'border-slate-200 dark:border-slate-600' : '' }}">
        <button
            type="button"
            wire:click="saveFields"
            wire:loading.attr="disabled"
            wire:target="saveFields"
            class="inline-flex min-h-[2.75rem] min-w-[10rem] items-center justify-center rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 px-6 py-2.5 text-base font-bold text-white shadow-lg shadow-sky-500/20 hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-60"
        >
            <span wire:loading.remove wire:target="saveFields">{{ config('atlas.ui.editor.save_button_label') }}</span>
            <span wire:loading wire:target="saveFields" class="inline-flex items-center gap-2">
                <svg class="h-5 w-5 animate-spin text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ config('atlas.ui.editor.saving_label') }}
            </span>
        </button>
        <button
            type="button"
            wire:click="clearSelection"
            class="{{ $isLive ? 'rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800' : 'rounded-lg border border-slate-600 px-4 py-2.5 text-sm font-medium text-slate-200 hover:bg-slate-800' }}"
        >
            Done
        </button>
    </div>
</div>
