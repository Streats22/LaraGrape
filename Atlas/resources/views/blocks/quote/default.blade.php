<blockquote class="mx-auto max-w-3xl border-l-4 border-indigo-500 pl-6 pr-4">
    <p class="text-lg italic leading-relaxed text-slate-800 dark:text-slate-200">“{{ $block->quote }}”</p>
    @if (! empty($block->author))
        <footer class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">— {{ $block->author }}</footer>
    @endif
</blockquote>
