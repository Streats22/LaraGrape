@php
    $url = $block->url ?? '#';
@endphp
<div class="px-4">
    <a
        href="{{ $url }}"
        @if (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
        class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold text-slate-700 underline-offset-4 transition hover:underline dark:text-slate-300"
    >
        {{ $block->label }}
    </a>
</div>
