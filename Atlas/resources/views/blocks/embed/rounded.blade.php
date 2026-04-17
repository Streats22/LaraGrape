@php
    $url = $block->url ?? '';
    $embedSrc = null;
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $m)) {
        $embedSrc = 'https://www.youtube.com/embed/'.$m[1];
    } elseif (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
        $embedSrc = 'https://player.vimeo.com/video/'.$m[1];
    }
@endphp
<div class="aspect-video w-full overflow-hidden rounded-3xl bg-slate-100 shadow-lg ring-1 ring-slate-900/5 dark:bg-slate-900 dark:ring-white/10">
    @if ($embedSrc)
        <iframe
            src="{{ $embedSrc }}"
            class="h-full w-full"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            loading="lazy"
            title="Embedded media"
        ></iframe>
    @else
        <div class="flex h-full min-h-[12rem] items-center justify-center p-4">
            <a href="{{ $url }}" class="text-sm font-medium text-indigo-600 underline dark:text-indigo-400" target="_blank" rel="noopener noreferrer">Open link</a>
        </div>
    @endif
</div>
