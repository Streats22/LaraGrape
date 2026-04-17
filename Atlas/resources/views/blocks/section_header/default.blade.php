<div class="mx-auto max-w-3xl px-4">
    @if (! empty($block->eyebrow))
        <p class="text-sm font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">{{ $block->eyebrow }}</p>
    @endif
    <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">{{ $block->title }}</h2>
    @if (! empty($block->subtitle))
        <p class="mt-3 whitespace-pre-wrap text-base leading-relaxed text-slate-600 dark:text-slate-400">{{ $block->subtitle }}</p>
    @endif
</div>
