<div class="inline-flex flex-wrap items-baseline gap-2 rounded-lg border border-slate-200 px-4 py-2 dark:border-slate-700">
    <span class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $block->label }}</span>
    <span class="text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $block->value }}@if (! empty($block->suffix))<span class="text-lg font-semibold text-slate-500">{{ $block->suffix }}</span>@endif</span>
</div>
