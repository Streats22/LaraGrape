<div class="flex flex-col gap-6 rounded-2xl border border-slate-200 p-6 dark:border-slate-700 sm:flex-row sm:items-start">
    @if (! empty($block->icon))
        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-2xl dark:bg-indigo-950/50" aria-hidden="true">{{ $block->icon }}</span>
    @endif
    <div>
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $block->title }}</h3>
        @if (! empty($block->description))
            <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $block->description }}</p>
        @endif
    </div>
</div>
