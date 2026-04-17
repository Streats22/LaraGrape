@php
    $url = $block->url ?? '#';
@endphp
<div class="px-4">
    <a
        href="{{ $url }}"
        @if (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
        class="inline-flex items-center justify-center rounded-lg bg-slate-700 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
    >
        {{ $block->label }}
    </a>
</div>
