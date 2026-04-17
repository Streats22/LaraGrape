@php
    $src = $block->src ?? '';
@endphp
<figure class="mx-auto max-w-3xl px-4">
    <img
        src="{{ $src }}"
        alt="{{ $block->alt ?? '' }}"
        class="h-auto w-full max-w-full rounded-3xl object-cover shadow-lg ring-1 ring-slate-900/5 dark:ring-white/10"
        loading="lazy"
    />
    @if (! empty($block->caption))
        <figcaption class="mt-2 text-center text-sm text-slate-500 dark:text-slate-400">{{ $block->caption }}</figcaption>
    @endif
</figure>
