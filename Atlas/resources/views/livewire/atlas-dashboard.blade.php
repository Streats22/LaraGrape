<div class="mx-auto max-w-4xl px-5 py-10">
    <header class="mb-8">
        <p class="mb-2 text-[0.65rem] font-bold uppercase tracking-widest text-sky-400">Atlas</p>
        <h1 class="text-2xl font-bold tracking-tight text-white">Pages</h1>
        <p class="mt-2 max-w-[52ch] text-sm text-slate-400">
            Create pages here, then open the live editor or builder. Slug is optional when creating — it is derived from the title.
        </p>
    </header>

    @if (session('atlas-status'))
        <p class="mb-6 rounded-lg border border-emerald-500/30 bg-emerald-950/40 px-4 py-3 text-sm text-emerald-200" role="status">
            {{ session('atlas-status') }}
        </p>
    @endif

    @if ($hasSetup)
        <p class="mb-6 text-sm">
            <a href="{{ route('atlas.setup') }}" class="text-sky-400 hover:underline">Adapter setup</a>
        </p>
    @endif

    <section class="mb-8 rounded-xl border border-slate-700/50 bg-slate-900/60 p-6">
        <h2 class="mb-4 text-[0.65rem] font-bold uppercase tracking-widest text-slate-500">New page</h2>
        <form wire:submit="createPage" class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end">
            <div class="min-w-0 flex-1 sm:max-w-xs">
                <label class="mb-1 block text-xs font-medium text-slate-400" for="atlas-create-title">Title</label>
                <input
                    id="atlas-create-title"
                    type="text"
                    wire:model="createTitle"
                    class="w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-white placeholder:text-slate-500 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500/40"
                    placeholder="e.g. About us"
                    autocomplete="off"
                />
                @error('createTitle')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="min-w-0 flex-1 sm:max-w-xs">
                <label class="mb-1 block text-xs font-medium text-slate-400" for="atlas-create-slug">Slug <span class="font-normal text-slate-500">(optional)</span></label>
                <input
                    id="atlas-create-slug"
                    type="text"
                    wire:model="createSlug"
                    class="w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-white placeholder:text-slate-500 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500/40"
                    placeholder="about-us"
                    autocomplete="off"
                />
                @error('createSlug')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <button
                type="submit"
                class="shrink-0 rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:brightness-110"
            >
                Add page
            </button>
        </form>
    </section>

    @if ($pages->isEmpty())
        <p class="rounded-xl border border-slate-700/60 bg-slate-900/60 p-6 text-sm text-slate-400">
            No pages yet. Use the form above to create your first page.
        </p>
    @else
        <ul class="divide-y divide-slate-700/50 rounded-xl border border-slate-700/50 bg-slate-900/60">
            @foreach ($pages as $p)
                <li class="px-4 py-4">
                    @if ($this->editingPageId === $p->id)
                        <form wire:submit="savePage" class="flex flex-col gap-4">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-400" for="atlas-edit-title-{{ $p->id }}">Title</label>
                                    <input
                                        id="atlas-edit-title-{{ $p->id }}"
                                        type="text"
                                        wire:model="editTitle"
                                        class="w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-white focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500/40"
                                    />
                                    @error('editTitle')
                                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-400" for="atlas-edit-slug-{{ $p->id }}">Slug</label>
                                    <input
                                        id="atlas-edit-slug-{{ $p->id }}"
                                        type="text"
                                        wire:model="editSlug"
                                        class="w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 font-mono text-sm text-white focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500/40"
                                    />
                                    @error('editSlug')
                                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="submit"
                                    class="rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow hover:brightness-110"
                                >
                                    Save
                                </button>
                                <button
                                    type="button"
                                    wire:click="cancelEdit"
                                    class="rounded-lg border border-slate-600 px-4 py-2 text-xs font-medium text-slate-200 hover:bg-slate-800"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-white">{{ $p->title }}</p>
                                <p class="font-mono text-xs text-slate-500">/{{ $p->slug }} · {{ $p->blocks_count }} blocks</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    wire:click="startEdit({{ $p->id }})"
                                    class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-medium text-slate-200 hover:bg-slate-800"
                                >
                                    Edit
                                </button>
                                @if ($hasLive)
                                    <a
                                        href="{{ route('atlas.page.live', $p) }}"
                                        class="rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow hover:brightness-110"
                                    >
                                        Live editor
                                    </a>
                                @endif
                                @if ($hasBuilder)
                                    <a
                                        href="{{ route('atlas.page.builder', $p) }}"
                                        class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-medium text-slate-200 hover:bg-slate-800"
                                    >
                                        Builder
                                    </a>
                                @endif
                                @if ($hasPreview && isset($previewUrls[$p->id]))
                                    <a
                                        href="{{ $previewUrls[$p->id] }}"
                                        class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-medium text-slate-200 hover:bg-slate-800"
                                        target="_blank"
                                        rel="noopener"
                                    >
                                        Preview
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <p class="mt-8 text-sm text-slate-500">
        <a href="{{ url('/') }}" class="text-sky-400 hover:underline">Back to site</a>
    </p>
</div>
