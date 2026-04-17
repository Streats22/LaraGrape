<div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 dark:border-amber-900/50 dark:bg-amber-950/40">
    <p class="font-semibold text-amber-900 dark:text-amber-100">{{ $block->title }}</p>
    @if (! empty($block->message))
        <p class="mt-1 whitespace-pre-wrap text-sm text-amber-800 dark:text-amber-200/90">{{ $block->message }}</p>
    @endif
</div>
