<blockquote class="mx-auto max-w-4xl px-4 text-center">
    <p class="text-2xl font-medium leading-snug tracking-tight text-slate-900 dark:text-white sm:text-3xl">“{{ $block->quote }}”</p>
    @if (! empty($block->author))
        <footer class="mt-6 text-base text-slate-500 dark:text-slate-400">— {{ $block->author }}</footer>
    @endif
</blockquote>
