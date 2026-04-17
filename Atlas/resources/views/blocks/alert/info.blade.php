<div class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 dark:border-sky-900/50 dark:bg-sky-950/40">
    <p class="font-semibold text-sky-900 dark:text-sky-100">{{ $block->title }}</p>
    @if (! empty($block->message))
        <p class="mt-1 text-sm text-sky-800 dark:text-sky-200/90 whitespace-pre-wrap">{{ $block->message }}</p>
    @endif
</div>
