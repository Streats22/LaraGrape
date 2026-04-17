@php
    $lines = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", (string) ($block->items ?? '')))));
@endphp
<ul class="mx-auto max-w-3xl list-inside list-disc space-y-2 px-4 marker:text-indigo-500">
    @foreach ($lines as $line)
        <li class="text-slate-700 dark:text-slate-300">{{ $line }}</li>
    @endforeach
</ul>
