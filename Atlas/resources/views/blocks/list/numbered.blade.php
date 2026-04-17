@php
    $lines = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", (string) ($block->items ?? '')))));
@endphp
<ol class="mx-auto max-w-3xl list-inside list-decimal space-y-2 px-4 marker:font-semibold marker:text-indigo-600">
    @foreach ($lines as $line)
        <li class="text-slate-700 dark:text-slate-300">{{ $line }}</li>
    @endforeach
</ol>
