<div class="mx-auto max-w-4xl px-5 py-10 pb-16">
    <header class="mb-8 rounded-2xl border border-slate-700/50 bg-slate-900/80 p-8 shadow-2xl shadow-black/20">
        <span class="mb-4 inline-flex items-center gap-1 rounded-full bg-sky-500/15 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-widest text-sky-400">Step 8 · Setup wizard</span>
        <h1 class="text-2xl font-bold tracking-tight text-white">Atlas setup</h1>
        <p class="mt-2 max-w-[52ch] text-sm leading-relaxed text-slate-400">
            Inspect your environment, then choose which Atlas adapters may boot. Selections are stored in the database (<code class="rounded bg-slate-800 px-1 font-mono text-xs">atlas_settings</code>), not in <code class="rounded bg-slate-800 px-1 font-mono text-xs">config/files</code>, so you can change them without redeploying.
        </p>
    </header>

    <section aria-labelledby="env-heading">
        <h2 id="env-heading" class="mb-4 text-base font-semibold text-white">Detected environment</h2>
        <div class="mb-8 grid grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-4">
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">PHP</dt>
                <dd class="font-mono text-sm font-semibold text-slate-100">{{ $detection['php'] }}</dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Laravel</dt>
                <dd class="font-mono text-sm font-semibold text-slate-100">{{ $detection['laravel'] }}</dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Environment</dt>
                <dd class="font-mono text-sm font-semibold text-slate-100">{{ $detection['environment'] }}</dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Database</dt>
                <dd class="break-words font-mono text-xs font-medium text-slate-400">{{ $detection['database_driver'] ?? '?' }} · {{ $detection['database_connection'] }}</dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Livewire</dt>
                <dd>
                    @if ($detection['livewire']['installed'])
                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/15 px-2 py-0.5 text-xs font-semibold text-emerald-400">{{ $detection['livewire']['version'] ?? 'installed' }}</span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-md bg-slate-500/15 px-2 py-0.5 text-xs font-semibold text-slate-500">Not detected</span>
                    @endif
                </dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Filament</dt>
                <dd>
                    @if ($detection['filament']['installed'])
                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/15 px-2 py-0.5 text-xs font-semibold text-emerald-400">{{ $detection['filament']['version'] ?? 'installed' }}</span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-md bg-slate-500/15 px-2 py-0.5 text-xs font-semibold text-slate-500">Not installed</span>
                    @endif
                </dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Nova</dt>
                <dd>
                    @if ($detection['nova']['installed'])
                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/15 px-2 py-0.5 text-xs font-semibold text-emerald-400">{{ $detection['nova']['version'] ?? 'installed' }}</span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-md bg-slate-500/15 px-2 py-0.5 text-xs font-semibold text-slate-500">Not installed</span>
                    @endif
                </dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Atlas registry</dt>
                <dd class="text-xs font-medium text-slate-400">{{ $detection['block_types'] }} block types · {{ $detection['field_types'] }} field types</dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Published config</dt>
                <dd>
                    @if ($detection['atlas_config_published'])
                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/15 px-2 py-0.5 text-xs font-semibold text-emerald-400">config/atlas.php</span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-md bg-slate-500/15 px-2 py-0.5 text-xs font-semibold text-slate-500">Using package defaults</span>
                    @endif
                </dd>
            </dl>
            <dl class="rounded-xl border border-slate-700/50 bg-slate-800/80 p-4">
                <dt class="mb-1 text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Published views</dt>
                <dd>
                    @if ($detection['atlas_views_published'])
                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/15 px-2 py-0.5 text-xs font-semibold text-emerald-400">resources/views/vendor/atlas</span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-md bg-slate-500/15 px-2 py-0.5 text-xs font-semibold text-slate-500">Package views</span>
                    @endif
                </dd>
            </dl>
        </div>
    </section>

    <section aria-labelledby="adapters-heading">
        <h2 id="adapters-heading" class="mb-4 text-base font-semibold text-white">Adapter access</h2>
        <p class="mb-4 max-w-[65ch] text-sm text-slate-400">
            Only classes listed in <code class="rounded bg-slate-800 px-1 font-mono text-xs">config('atlas.adapters')</code> can be toggled. Uncheck all and save to store an empty allow-list (nothing boots until you add selections again), or use “Reset” to clear the DB filter and boot every configured adapter.
        </p>

        @if (session('atlas_setup_message'))
            <div class="mb-5 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400" role="status">{{ session('atlas_setup_message') }}</div>
        @endif

        @if (count($configuredAdapters) === 0)
            <p class="text-sm text-slate-500">No adapters in config. Register classes in <code class="rounded bg-slate-800 px-1 font-mono text-xs">config/atlas.php</code> under <code class="rounded bg-slate-800 px-1 font-mono text-xs">adapters</code>.</p>
        @else
            <form wire:submit="save">
                @foreach ($configuredAdapters as $class)
                    <label class="mb-2 flex cursor-pointer items-start gap-3 rounded-lg border border-slate-700/50 bg-slate-900/80 p-4">
                        <input type="checkbox" value="{{ $class }}" wire:model="enabledAdapterClasses" class="mt-1 accent-sky-500" />
                        <code class="flex-1 break-all font-mono text-xs text-slate-400">{{ $class }}</code>
                    </label>
                @endforeach
                <div class="mt-5 flex flex-wrap gap-3">
                    <button type="submit" class="rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-500/25 hover:brightness-110">Save adapter selection</button>
                    <button type="button" class="rounded-lg border border-slate-600 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-slate-800" wire:click="clearAdapterFilter">Reset to all adapters</button>
                </div>
            </form>
        @endif

        <p class="mt-4 max-w-[60ch] text-xs text-slate-500">
            Adapter <code class="rounded bg-slate-800 px-1 font-mono">boot()</code> already ran for this request. After saving, reload the app or run a new request so the new allow-list applies. Use <code class="rounded bg-slate-800 px-1 font-mono">php artisan config:clear</code> if you also change config files.
        </p>
    </section>
</div>
