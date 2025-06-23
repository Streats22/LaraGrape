@php
    $id = $getId();
    $isDisabled = $isDisabled();
    $statePath = $getStatePath();
    $height = $getHeight();
    $state = $getState();
    
    // Load blocks dynamically from BlockService
    $blockService = app(\App\Services\BlockService::class);
    $blocks = $blockService->getGrapesJsBlocks();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="grapesjs-editor-wrapper" id="wrapper-{{ $id }}">
        <!-- Fullscreen Toggle Button -->
        <div class="grapesjs-controls">
            <button 
                type="button" 
                class="fullscreen-toggle-btn"
                onclick="toggleFullscreen('{{ $id }}')"
                title="Toggle Fullscreen Mode (Press Escape to exit)"
            >
                <svg class="fullscreen-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                </svg>
                <svg class="exit-fullscreen-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                    <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
                </svg>
            </button>
        </div>
        <div 
            id="{{ $id }}"
            class="grapesjs-editor"
            style="height: {{ $height }}; min-height: 400px; border: 1.5px solid #e5e7eb; background: #fff;"
            data-state-path="{{ $statePath }}"
            data-current-state="{{ json_encode($state) }}"
            data-height="{{ $height }}"
            data-disabled="{{ $isDisabled ? 'true' : 'false' }}"
            data-blocks="{{ json_encode($blocks) }}"
        ></div>
        {{ $getChildComponentContainer() }}
    </div>
    
    @push('scripts')
    <script>
        // Fullscreen toggle function
        function toggleFullscreen(editorId) {
            const wrapper = document.getElementById(`wrapper-${editorId}`);
            const btn = wrapper.querySelector('.fullscreen-toggle-btn');
            const fullscreenIcon = btn.querySelector('.fullscreen-icon');
            const exitIcon = btn.querySelector('.exit-fullscreen-icon');
            const editorDiv = wrapper.querySelector('.grapesjs-editor');
            if (wrapper.classList.contains('fullscreen')) {
                wrapper.classList.remove('fullscreen');
                fullscreenIcon.style.display = 'block';
                exitIcon.style.display = 'none';
                btn.title = 'Toggle Fullscreen Mode (Press Escape to exit)';
                editorDiv.style.height = editorDiv.dataset.height || '600px';
                document.body.style.overflow = '';
            } else {
                wrapper.classList.add('fullscreen');
                fullscreenIcon.style.display = 'none';
                exitIcon.style.display = 'block';
                btn.title = 'Exit Fullscreen';
                editorDiv.style.height = 'calc(100vh - 120px)';
                document.body.style.overflow = 'hidden';
            }
        }
        // Keyboard support for fullscreen exit
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.grapesjs-editor-wrapper.fullscreen').forEach(wrapper => {
                    const btn = wrapper.querySelector('.fullscreen-toggle-btn');
                    if (btn) btn.click();
                });
            }
        });
        // Always initialize GrapesJS when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const editorElement = document.getElementById('{{ $id }}');
            if (editorElement && typeof grapesjs !== 'undefined') {
                const statePath = editorElement.dataset.statePath;
                const currentState = JSON.parse(editorElement.dataset.currentState || '{}');
                const height = editorElement.dataset.height;
                const isDisabled = editorElement.dataset.disabled === 'true';
                const blocks = JSON.parse(editorElement.dataset.blocks || '[]');
                
                const editor = grapesjs.init({
                    container: editorElement,
                    height: height,
                    width: '100%',
                    fromElement: false,
                    showOffsets: true,
                    noticeOnUnload: false,
                    storageManager: false,
                    canvas: {
                        styles: [
                            'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css'
                        ],
                        scripts: [],
                    },
                    blockManager: {
                        blocks: blocks
                    }
                });
                // Load existing content
                if (currentState && currentState.html) {
                    editor.setComponents(currentState.html);
                }
                if (currentState && currentState.css) {
                    editor.setStyle(currentState.css);
                }
                // Update hidden field on changes
                const updateState = () => {
                    const html = editor.getHtml();
                    const css = editor.getCss();
                    const data = {
                        html: html,
                        css: css,
                        data: editor.getProjectData()
                    };
                    const hiddenInput = document.querySelector(`input[name='${statePath}']`);
                    if (hiddenInput) {
                        hiddenInput.value = JSON.stringify(data);
                        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                };
                editor.on('change:changedComponent change:changedStyle', updateState);
                if (isDisabled) {
                    editor.Commands.run('core:canvas-clear');
                }
                setTimeout(() => {
                    editor.refresh();
                }, 100);
            }
        });
    </script>
    @endpush
    <style>
    .grapesjs-editor-wrapper {
        width: 100%;
        position: relative;
        transition: all 0.3s ease;
    }
    .grapesjs-editor-wrapper.fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 99999 !important;
        background: white;
        /* padding: 20px; */
        box-sizing: border-box;
        isolation: isolate;
    }
    .grapesjs-controls {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 100000 !important;
        display: flex;
        gap: 8px;
    }
    .fullscreen-toggle-btn {
        background: rgba(59, 130, 246, 0.9);
        border: 2px solid #3b82f6;
        border-radius: 8px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100001 !important;
        position: relative;
        min-width: 44px;
        min-height: 44px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .fullscreen-toggle-btn:hover {
        background: rgba(59, 130, 246, 1);
        border-color: #2563eb;
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        transform: translateY(-1px);
    }
    .fullscreen-toggle-btn svg {
        color: white;
        width: 20px;
        height: 20px;
    }
    .fullscreen-toggle-btn:hover svg {
        color: white;
    }
    .grapesjs-editor {
        border-radius: 8px;
        overflow: hidden;
        width: 100% !important;
        min-height: 400px;
        background: #ffffff;
        transition: height 0.3s ease;
    }
    .grapesjs-editor-wrapper.fullscreen .grapesjs-editor {
        height: calc(100vh - 120px) !important;
        border-radius: 0;
        border: none;
        z-index: 99999 !important;
    }
    @media (max-width: 1024px) {
        .grapesjs-editor {
            min-height: 300px;
        }
    }
    @media (max-width: 768px) {
        .grapesjs-controls {
            top: 10px;
            right: 10px;
        }
        .fullscreen-toggle-btn {
            padding: 10px;
            min-width: 40px;
            min-height: 40px;
        }
        .fullscreen-toggle-btn svg {
            width: 18px;
            height: 18px;
        }
    }
    </style>
</x-dynamic-component>
