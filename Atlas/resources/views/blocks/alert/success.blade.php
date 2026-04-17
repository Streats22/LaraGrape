<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 dark:border-emerald-900/50 dark:bg-emerald-950/40">
    <p class="font-semibold text-emerald-900 dark:text-emerald-100">{{ $block->title }}</p>
    @if (! empty($block->message))
        <p class="mt-1 whitespace-pre-wrap text-sm text-emerald-800 dark:text-emerald-200/90">{{ $block->message }}</p>
    @endif
</div>
