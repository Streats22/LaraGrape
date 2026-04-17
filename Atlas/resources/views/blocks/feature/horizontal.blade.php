<div class="flex flex-row items-start gap-4 rounded-2xl bg-slate-50 p-4 dark:bg-slate-900/60">
    @if (! empty($block->icon))
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-xl shadow-sm dark:bg-slate-800" aria-hidden="true">{{ $block->icon }}</span>
    @endif
    <div class="min-w-0 flex-1">
        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $block->title }}</h3>
        @if (! empty($block->description))
            <p class="mt-1 whitespace-pre-wrap text-sm text-slate-600 dark:text-slate-400">{{ $block->description }}</p>
        @endif
    </div>
</div>
