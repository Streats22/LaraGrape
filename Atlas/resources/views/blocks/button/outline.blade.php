@php
    $url = $block->url ?? '#';
@endphp
<div class="px-4">
    <a
        href="{{ $url }}"
        @if (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
        class="inline-flex items-center justify-center rounded-lg border-2 border-indigo-600 bg-transparent px-6 py-3 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50 dark:border-indigo-400 dark:text-indigo-400 dark:hover:bg-indigo-950/40"
    >
        {{ $block->label }}
    </a>
</div>
