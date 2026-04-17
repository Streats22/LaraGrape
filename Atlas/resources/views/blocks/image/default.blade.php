@php
    $src = $block->src ?? '';
@endphp
<figure class="mx-auto max-w-3xl px-4">
    <img
        src="{{ $src }}"
        alt="{{ $block->alt ?? '' }}"
        class="h-auto w-full max-w-full rounded-lg object-cover"
        loading="lazy"
    />
    @if (! empty($block->caption))
        <figcaption class="mt-2 text-center text-sm text-slate-500 dark:text-slate-400">{{ $block->caption }}</figcaption>
    @endif
</figure>
