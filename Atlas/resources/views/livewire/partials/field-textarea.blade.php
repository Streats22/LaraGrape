@props(['field'])
<div class="mb-4">
    <label class="mb-1 block text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500" for="atlas-field-{{ $field['key'] }}">{{ $field['label'] }}</label>
    <textarea
        id="atlas-field-{{ $field['key'] }}"
        rows="6"
        class="min-h-[5rem] w-full resize-y rounded-lg border border-slate-600 bg-slate-800 px-2 py-2 text-sm leading-relaxed text-slate-100 placeholder:text-slate-500 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
        wire:model.live="fieldValues.{{ $field['key'] }}"
    ></textarea>
</div>
