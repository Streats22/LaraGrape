@php
    $src = $block->src ?? '';
@endphp
<figure class="w-full max-w-none px-0 sm:px-4">
    <img
        src="{{ $src }}"
        alt="{{ $block->alt ?? '' }}"
        class="h-auto w-full object-cover"
        loading="lazy"
    />
    @if (! empty($block->caption))
        <figcaption class="mt-2 px-4 text-center text-sm text-slate-500 dark:text-slate-400">{{ $block->caption }}</figcaption>
    @endif
</figure>
