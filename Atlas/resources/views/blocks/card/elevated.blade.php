<div class="rounded-2xl bg-white p-6 shadow-xl shadow-slate-900/10 dark:bg-slate-800 dark:shadow-none">
    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $block->title }}</h3>
    @if (! empty($block->body))
        <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $block->body }}</p>
    @endif
    @if (! empty($block->link_url) && ! empty($block->link_label))
        <a
            href="{{ $block->link_url }}"
            class="mt-4 inline-flex text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
            @if (\Illuminate\Support\Str::startsWith($block->link_url, ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
        >
            {{ $block->link_label }} →
        </a>
    @endif
</div>
