<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-900/50 dark:bg-red-950/40">
    <p class="font-semibold text-red-900 dark:text-red-100">{{ $block->title }}</p>
    @if (! empty($block->message))
        <p class="mt-1 whitespace-pre-wrap text-sm text-red-800 dark:text-red-200/90">{{ $block->message }}</p>
    @endif
</div>
