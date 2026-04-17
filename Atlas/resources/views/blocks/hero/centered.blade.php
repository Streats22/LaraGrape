<section class="rounded-2xl bg-gradient-to-br from-sky-500/15 via-transparent to-indigo-600/15 px-6 py-12 text-center sm:px-10">
    <div class="mx-auto max-w-4xl">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-4xl md:text-5xl">{{ $block->title }}</h1>
        @if (! empty($block->subtitle))
            <p class="mt-4 whitespace-pre-wrap text-lg leading-relaxed text-slate-600 dark:text-slate-400">{{ $block->subtitle }}</p>
        @endif
    </div>
</section>
