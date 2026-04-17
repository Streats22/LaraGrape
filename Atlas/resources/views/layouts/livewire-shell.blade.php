<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }} · Atlas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|jetbrains-mono:400,500" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
                    },
                },
            },
        };
    </script>
    <style>
        .atlas-sortable-ghost { opacity: 0.55 !important; }
        .atlas-sortable-chosen { box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.45); }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-950 bg-[radial-gradient(ellipse_1200px_600px_at_10%_-10%,rgba(56,189,248,0.08),transparent)] bg-[radial-gradient(ellipse_800px_400px_at_90%_0%,rgba(99,102,241,0.06),transparent)] text-slate-100 antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
