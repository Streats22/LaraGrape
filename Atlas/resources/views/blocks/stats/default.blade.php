<div class="rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 px-6 py-8 text-center dark:from-slate-900 dark:to-slate-800">
    <p class="text-sm font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $block->label }}</p>
    <p class="mt-2 text-4xl font-bold tabular-nums text-slate-900 dark:text-white sm:text-5xl">
        {{ $block->value }}@if (! empty($block->suffix))<span class="text-2xl font-semibold text-slate-500 dark:text-slate-400">{{ $block->suffix }}</span>@endif
    </p>
</div>
