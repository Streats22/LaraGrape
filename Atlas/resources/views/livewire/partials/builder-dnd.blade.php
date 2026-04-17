@php
    $builderRootId = $builderRootId ?? 'atlas-page-builder';
    $dropZoneId = $dropZoneId ?? 'atlas-canvas-drop-zone';
    $sortableId = $sortableId ?? 'atlas-sortable-blocks';
    $paletteId = $paletteId ?? 'atlas-block-palette';
    $dropLineId = $dropLineId ?? null;
    $atlasDndConfig = json_encode([
        'builderRootId' => $builderRootId,
        'dropZoneId' => $dropZoneId,
        'sortableId' => $sortableId,
        'paletteId' => $paletteId,
        'dropLineId' => $dropLineId,
    ], JSON_THROW_ON_ERROR);
@endphp

{{-- Single <script> block: Livewire only executes the *first* script tag in @script (see extractScriptTagContent). --}}
<script>
    (() => {
        const MIME = 'application/x-atlas-block-type';
        const CONFIG = {!! $atlasDndConfig !!};
        const SORTABLE_URL = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js';

        let sortableCanvas = null;
        let dndAbort = null;
        let morphTimer = null;
        let paletteDragInProgress = false;
        let morphHooked = false;

        const wire = () => $wire;

        const loadSortable = (onReady) => {
            if (typeof Sortable !== 'undefined') {
                onReady();
                return;
            }
            const sel = 'script[data-atlas-sortable-cdn="1"]';
            const existing = document.querySelector(sel);
            if (existing) {
                existing.addEventListener('load', () => onReady(), { once: true });
                if (typeof Sortable !== 'undefined') onReady();
                return;
            }
            const s = document.createElement('script');
            s.src = SORTABLE_URL;
            s.async = true;
            s.setAttribute('data-atlas-sortable-cdn', '1');
            s.onload = () => onReady();
            s.onerror = () => console.error('[Atlas] Failed to load Sortable from CDN');
            document.head.appendChild(s);
        };

        const blockRows = (canvas) =>
            [...canvas.children].filter((n) => n.nodeType === 1 && n.hasAttribute('data-id'));

        const dropIndex = (clientY) => {
            const canvas = document.getElementById(CONFIG.sortableId);
            if (!canvas) return 0;
            const rows = blockRows(canvas);
            if (!rows.length) return 0;
            for (let i = 0; i < rows.length; i++) {
                const r = rows[i].getBoundingClientRect();
                const mid = r.top + r.height / 2;
                if (clientY < mid) return i;
            }
            return rows.length;
        };

        const hideDropLine = () => {
            if (!CONFIG.dropLineId) return;
            const line = document.getElementById(CONFIG.dropLineId);
            if (!line) return;
            line.style.display = 'none';
            line.style.opacity = '0';
        };

        const positionDropLine = (clientY) => {
            if (!CONFIG.dropLineId) return;
            const line = document.getElementById(CONFIG.dropLineId);
            const zone = document.getElementById(CONFIG.dropZoneId);
            const canvas = document.getElementById(CONFIG.sortableId);
            if (!line || !zone || !canvas) return;

            const idx = dropIndex(clientY);
            const rows = blockRows(canvas);
            const cTop = canvas.offsetTop;

            let y;
            if (!rows.length) {
                y = cTop + 12;
            } else if (idx === 0) {
                y = cTop + rows[0].offsetTop;
            } else if (idx >= rows.length) {
                const last = rows[rows.length - 1];
                y = cTop + last.offsetTop + last.offsetHeight;
            } else {
                const prev = rows[idx - 1];
                const next = rows[idx];
                y = cTop + (prev.offsetTop + prev.offsetHeight + next.offsetTop) / 2;
            }

            line.style.display = 'block';
            line.style.top = `${y}px`;
            line.style.opacity = '1';
        };

        const paletteDragActive = (e) => {
            const types = e.dataTransfer?.types ? [...e.dataTransfer.types] : [];
            if (types.includes(MIME) || types.includes('text/plain')) return true;
            if (paletteDragInProgress && types.length === 0) return true;
            return false;
        };

        const bindNativeDnD = () => {
            if (dndAbort) dndAbort.abort();
            dndAbort = new AbortController();
            const { signal } = dndAbort;
            const zone = document.getElementById(CONFIG.dropZoneId);
            if (!zone) return;

            const clearHighlight = () => {
                zone.classList.remove('border-sky-500/50', 'bg-sky-500/5');
                hideDropLine();
            };

            zone.addEventListener(
                'dragover',
                (e) => {
                    if (!paletteDragActive(e)) return;
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'copy';
                    zone.classList.add('border-sky-500/50', 'bg-sky-500/5');
                    positionDropLine(e.clientY);
                },
                { signal },
            );

            zone.addEventListener(
                'dragleave',
                (e) => {
                    if (!zone.contains(e.relatedTarget)) clearHighlight();
                },
                { signal },
            );

            zone.addEventListener(
                'drop',
                (e) => {
                    const type = (e.dataTransfer.getData(MIME) || e.dataTransfer.getData('text/plain') || '').trim();
                    if (!type) return;
                    e.preventDefault();
                    clearHighlight();
                    const idx = dropIndex(e.clientY);
                    wire().addBlockFromPalette(type, idx);
                },
                { signal },
            );

            document.addEventListener('dragend', clearHighlight, { signal });

            const builder = document.getElementById(CONFIG.builderRootId);
            if (builder) {
                builder.addEventListener(
                    'dblclick',
                    (e) => {
                        const el = e.target.closest('[data-atlas-palette-type]');
                        if (!el) return;
                        e.preventDefault();
                        const type = (el.getAttribute('data-atlas-palette-type') || '').trim();
                        if (!type) return;
                        const canvas = document.getElementById(CONFIG.sortableId);
                        const n = canvas ? blockRows(canvas).length : 0;
                        wire().addBlockFromPalette(type, n);
                    },
                    { signal },
                );
            }
        };

        const initSortable = () => {
            const canvas = document.getElementById(CONFIG.sortableId);
            if (!canvas || typeof Sortable === 'undefined') return;

            if (sortableCanvas) {
                sortableCanvas.destroy();
                sortableCanvas = null;
            }

            sortableCanvas = Sortable.create(canvas, {
                handle: '.atlas-row-handle',
                animation: 180,
                draggable: '[data-id]',
                forceFallback: true,
                easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
                ghostClass: 'atlas-sortable-ghost',
                chosenClass: 'atlas-sortable-chosen',
                onEnd: () => {
                    const ids = blockRows(canvas).map((n) => parseInt(n.getAttribute('data-id'), 10));
                    if (ids.length) {
                        wire().reorder(ids);
                    }
                },
            });
        };

        const run = () => {
            bindNativeDnD();
            initSortable();
        };

        const scheduleRun = () => {
            clearTimeout(morphTimer);
            morphTimer = setTimeout(() => queueMicrotask(run), 80);
        };

        const ensureMorphHook = () => {
            if (morphHooked) return;
            const attach = () => {
                if (morphHooked || typeof Livewire === 'undefined' || typeof Livewire.hook !== 'function') return;
                morphHooked = true;
                Livewire.hook('morph.updated', () => scheduleRun());
            };
            document.addEventListener('livewire:init', attach, { once: true });
            queueMicrotask(attach);
        };

        document.addEventListener('livewire:navigated', () => scheduleRun());

        document.addEventListener(
            'dragstart',
            (e) => {
                const el = e.target.closest('[data-atlas-palette-type]');
                paletteDragInProgress = !!el;
                if (!el) return;
                const type = el.getAttribute('data-atlas-palette-type');
                e.dataTransfer.setData(MIME, type);
                e.dataTransfer.setData('text/plain', type);
                e.dataTransfer.effectAllowed = 'copy';
            },
            true,
        );

        document.addEventListener(
            'dragend',
            () => {
                paletteDragInProgress = false;
            },
            true,
        );

        ensureMorphHook();
        loadSortable(() => {
            queueMicrotask(run);
        });
    })();
</script>
