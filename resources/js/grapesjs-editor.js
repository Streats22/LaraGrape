/**
 * LaraGrape Unified GrapesJS Editor
 * Works for both frontend and backend (Filament) contexts
 */

// Import grapesjs-parser-postcss if using a bundler (uncomment if needed)
// import parserPostCSS from 'grapesjs-parser-postcss';

// Helper to fetch rendered block preview from backend
async function fetchBlockPreview(blockId) {
    const url = `/admin/block-preview/${blockId}`;
    try {
        const response = await fetch(url, { credentials: 'same-origin' });
        if (!response.ok) throw new Error('Failed to fetch block preview');
        return await response.text();
    } catch (e) {
        return `<div style='color:red;'>Preview error: ${e.message}</div>`;
    }
}

class LaraGrapeGrapesJsEditor {
    constructor(options = {}) {
        this.options = {
            containerId: 'grapejs-editor',
            mode: 'frontend', // 'frontend' or 'backend'
            saveUrl: '',
            blocks: [],
            initialData: {},
            statePath: '', // for Filament
            isDisabled: false,
            height: '100vh',
            onSave: null, // custom save handler
            ...options
        };
        
        this.editor = null;
        this.wrapper = null;
        this.fullscreenBtn = null;
        this.isFormSubmitting = false; // Track form submission state
        this.backupKey = `grapesjs_backup_${this.options.containerId}`;
        
        // Initialize the editor
        this.init();
    }

    init() {
        this.wrapper = document.getElementById(`wrapper-${this.options.containerId}`) || document.querySelector('.grapejs-editor-wrapper');
        this.fullscreenBtn = this.wrapper?.querySelector('.fullscreen-toggle-btn');
        if (typeof grapesjs === 'undefined') {
            console.error('GrapesJS is not loaded');
            return;
        }
        
        // Store the editor instance on the DOM element for global access
        if (this.wrapper) {
            this.wrapper.laraGrapeEditor = this;
        }
        
        this.initializeEditor();
        this.setupEventListeners();
    }

    async initializeEditor() {
        const editorElement = document.getElementById(this.options.containerId);
        if (!editorElement) {
            console.error(`Editor element with ID ${this.options.containerId} not found`);
            return;
        }
        
        console.log('Initializing GrapesJS editor with options:', {
            containerId: this.options.containerId,
            mode: this.options.mode,
            isDisabled: this.options.isDisabled,
            statePath: this.options.statePath,
            initialData: this.options.initialData
        });
        
        // Add PostCSS parser plugin for better CSS variable support
        const plugins = [];
        if (typeof parserPostCSS !== 'undefined') {
            plugins.push(parserPostCSS);
        } else if (typeof grapesjsParserPostcss !== 'undefined') {
            plugins.push(grapesjsParserPostcss);
        }
        
        // Instead of using static blocks, fetch previews dynamically
        const blockManagerBlocks = [];
        for (const block of this.options.blocks) {
            // Only fetch preview for file-based blocks (not custom or dynamic blocks)
            if (block.id && !block.is_custom) {
                const html = await fetchBlockPreview(block.id);
                blockManagerBlocks.push({ ...block, content: html });
            } else {
                blockManagerBlocks.push(block);
            }
        }
        
        this.editor = grapesjs.init({
            container: editorElement,
            height: this.options.height,
            width: '100%',
            fromElement: false,
            showOffsets: true,
            noticeOnUnload: false,
            storageManager: false,
            canvas: {
                styles: window.grapesjsCanvasStyles || [],
                scripts: [],
            },
            blockManager: {
                blocks: blockManagerBlocks
            },
            plugins,
        });
        
        // Add debugging to detect when editor gets cleared
        this.editor.on('component:selected', () => {
            console.log('Editor component selected');
        });
        
        this.editor.on('component:add', () => {
            console.log('Editor component added');
        });
        
        this.editor.on('component:remove', () => {
            console.log('Editor component removed');
        });
        
        this.editor.on('canvas:clear', () => {
            console.log('WARNING: Editor canvas cleared! This might be causing the blank editor issue.');
            // Try to restore from backup if this happens during form submission
            if (this.isFormSubmitting) {
                console.log('Form submission detected, attempting to restore from backup...');
                setTimeout(() => {
                    const backupData = this.restoreFromBackup();
                    if (backupData && backupData.html) {
                        this.editor.setComponents(backupData.html);
                        if (backupData.css) {
                            this.editor.setStyle(backupData.css);
                        }
                        console.log('Content restored from backup after canvas clear');
                    }
                }, 100);
            }
        });
        
        this.editor.on('destroy', () => {
            console.log('WARNING: Editor destroyed! This might be causing the blank editor issue.');
        });
        
        // Check if disabled before loading content
        if (this.options.isDisabled) {
            console.log('Editor is disabled, clearing canvas');
            this.editor.Commands.run('core:canvas-clear');
        } else {
            // Load existing content
            this.loadExistingContent();
        }
        
        // Setup change listeners
        this.setupChangeListeners();
        
        // Setup form submission handlers
        this.setupFilamentFormSubmission();
        
        // Setup other event listeners
        this.setupEventListeners();
        
        // Refresh the editor and inject styles
        setTimeout(() => {
            this.editor.refresh();
        }, 100);
        setTimeout(() => {
            injectStylesIntoGrapesJsIframe(this.editor, window.grapesjsCanvasStyles);
        }, 500);
        
        console.log('GrapesJS editor initialized successfully');
    }

    loadExistingContent() {
        const data = this.options.initialData;
        console.log('Loading existing content with data:', data);
        
        // Handle different data structures
        let html = null;
        let css = null;
        
        if (data) {
            // If data has html and css directly (from original_grapesjs)
            if (data.html && data.css) {
                html = data.html;
                css = data.css;
            }
            // If data has grapesjs_data with original_grapesjs
            else if (data.grapesjs_data && data.grapesjs_data.original_grapesjs) {
                html = data.grapesjs_data.original_grapesjs.html;
                css = data.grapesjs_data.original_grapesjs.css;
            }
            // If data has grapesjs_data with html and css directly
            else if (data.grapesjs_data && data.grapesjs_data.html) {
                html = data.grapesjs_data.html;
                css = data.grapesjs_data.css;
            }
            // If data is wrapped in grapesjs_data object (from Filament form)
            else if (data.grapesjs_data) {
                const grapesjsData = data.grapesjs_data;
                if (grapesjsData.original_grapesjs) {
                    html = grapesjsData.original_grapesjs.html;
                    css = grapesjsData.original_grapesjs.css;
                } else if (grapesjsData.html) {
                    html = grapesjsData.html;
                    css = grapesjsData.css;
                }
            }
        }
        
        // If no content found, try to restore from localStorage backup
        if (!html && !css) {
            const backupData = this.restoreFromBackup();
            if (backupData) {
                html = backupData.html;
                css = backupData.css;
                console.log('Restored content from localStorage backup');
            }
        }
        
        console.log('Final HTML to load:', html);
        console.log('Final CSS to load:', css);
        
        if (html) {
            console.log('Setting components with HTML:', html);
            this.editor.setComponents(html);
        } else {
            console.log('No HTML content found, editor will be empty');
        }
        
        if (css) {
            console.log('Setting styles with CSS:', css);
            this.editor.setStyle(css);
        } else {
            console.log('No CSS content found');
        }
        
        // Debug: Check if editor has content after loading
        setTimeout(() => {
            const currentHtml = this.editor.getHtml();
            const currentCss = this.editor.getCss();
            console.log('Editor content after loading - HTML:', currentHtml);
            console.log('Editor content after loading - CSS:', currentCss);
        }, 100);
    }

    restoreFromBackup() {
        try {
            const backupData = localStorage.getItem(this.backupKey);
            if (backupData) {
                const data = JSON.parse(backupData);
                console.log('Found backup data in localStorage:', data);
                return data;
            }
        } catch (error) {
            console.error('Error restoring from backup:', error);
        }
        return null;
    }
    
    // Check if editor is empty and restore if needed
    checkAndRestoreContent() {
        if (!this.editor) return;
        
        const components = this.editor.getComponents();
        const html = this.editor.getHtml();
        
        // Check if editor is essentially empty (no meaningful content)
        const isEmpty = !components.length || 
                       (html && html.trim() === '<body></body>') ||
                       (html && html.trim() === '<body><div></div></body>');
        
        if (isEmpty && this.isFormSubmitting) {
            console.log('Editor appears empty during form submission, attempting to restore...');
            const backupData = this.restoreFromBackup();
            if (backupData && backupData.html) {
                this.editor.setComponents(backupData.html);
                if (backupData.css) {
                    this.editor.setStyle(backupData.css);
                }
                console.log('Content restored successfully');
                return true;
            }
        }
        
        return false;
    }

    setupChangeListeners() {
        const updateState = () => {
            if (this.options.mode === 'backend') {
                this.updateFilamentFormState();
            }
        };
        // Robust: listen to all relevant events
        this.editor.on('component:add', updateState);
        this.editor.on('component:remove', updateState);
        this.editor.on('component:update', updateState);
        this.editor.on('change:changedComponent', updateState);
        this.editor.on('change:changedStyle', updateState);
        this.editor.on('component:selected', updateState);
        this.editor.on('component:deselected', updateState);
        this.editor.on('style:change', updateState);
        this.editor.on('canvas:drop', updateState);
        this.editor.on('canvas:dragend', updateState);
        this.editor.on('change', updateState);
    }

    updateFilamentFormState(data = null) {
        if (!this.editor) return;
        
        const html = this.editor.getHtml();
        const css = this.editor.getCss();
        const editorData = data || {
            html: html,
            css: css,
            data: this.editor.getProjectData(),
            last_updated: new Date().toISOString()
        };
        
        // Wrap the data in the structure that Filament expects
        const formData = {
            grapesjs_data: editorData
        };
        
        console.log('Updating Filament form state:', {
            statePath: this.options.statePath,
            data: formData
        });
        
        const form = this.wrapper.closest('form');
        if (form) {
            // Method 1: Update hidden input field by name
            let hiddenInput = form.querySelector(`input[name="${this.options.statePath}"]`);
            if (hiddenInput) {
                hiddenInput.value = JSON.stringify(formData);
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                console.log('Updated hidden input by name:', this.options.statePath);
            }
            
            // Method 2: Find Filament field wrapper and update its input
            const fieldWrapper = this.wrapper.closest('[data-field-wrapper]');
            if (fieldWrapper) {
                const filamentInput = fieldWrapper.querySelector('input[type="hidden"]');
                if (filamentInput) {
                    filamentInput.value = JSON.stringify(formData);
                    filamentInput.dispatchEvent(new Event('input', { bubbles: true }));
                    filamentInput.dispatchEvent(new Event('change', { bubbles: true }));
                    console.log('Updated Filament field wrapper input');
                }
            }
            
            // Method 3: Create hidden input if it doesn't exist
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = this.options.statePath;
                form.appendChild(hiddenInput);
                hiddenInput.value = JSON.stringify(formData);
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                console.log('Created and updated new hidden input for:', this.options.statePath);
            }
            
            // Method 4: Trigger Filament-specific events
            if (fieldWrapper) {
                fieldWrapper.dispatchEvent(new CustomEvent('filament:field-changed', {
                    detail: { value: formData },
                    bubbles: true
                }));
            }
            
            form.dispatchEvent(new CustomEvent('filament:form-changed', {
                detail: { field: this.options.statePath, value: formData },
                bubbles: true
            }));
            
            console.log('Filament form state update completed');
        }
    }

    // Method to sync content before form submission
    syncToFormBeforeSubmit() {
        if (this.options.mode === 'backend' && this.editor) {
            console.log('Syncing GrapesJS content to form before submit...');
            
            // Set form submission flag
            this.isFormSubmitting = true;
            
            // Reset flag after 5 seconds to prevent false positives
            setTimeout(() => {
                this.isFormSubmitting = false;
            }, 5000);
            
            try {
                // Get current editor content
                const html = this.editor.getHtml();
                const css = this.editor.getCss();
                const data = {
                    html: html,
                    css: css,
                    data: this.editor.getProjectData(),
                    last_updated: new Date().toISOString()
                };
                
                console.log('Current editor content to sync:', data);
                
                // Update Filament form state with the correct structure
                this.updateFilamentFormState(data);
                
                // Store backup in localStorage
                localStorage.setItem(this.backupKey, JSON.stringify(data));
                console.log('Backup data stored in localStorage');
                
                // Dispatch custom event for other components to listen to
                const event = new CustomEvent('grapesjs-sync', {
                    detail: {
                        field: this.options.statePath,
                        data: { grapesjs_data: data }
                    }
                });
                document.dispatchEvent(event);
                
                console.log('GrapesJS content synced successfully');
                
            } catch (error) {
                console.error('Error syncing GrapesJS content:', error);
                this.isFormSubmitting = false; // Reset flag on error
            }
        }
    }

    async saveContent() {
        if (this.options.mode === 'backend') {
            // Backend: Make AJAX request to save endpoint
            const html = this.editor.getHtml();
            const css = this.editor.getCss();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            if (!csrfToken) {
                alert('CSRF token not found. Please refresh the page and try again.');
                return;
            }
            
            // Get the save URL from the editor element data
            const editorElement = document.getElementById(this.options.containerId);
            let saveUrl = editorElement?.dataset.saveUrl;
            
            // If no save URL is provided, try to construct it from the current page
            if (!saveUrl) {
                const pageId = editorElement?.dataset.pageId;
                if (pageId) {
                    saveUrl = `/admin/pages/${pageId}/save-grapesjs`;
                } else {
                    // Try to extract page ID from the current URL
                    const currentUrl = window.location.pathname;
                    const match = currentUrl.match(/\/admin\/pages\/(\d+)\/edit/);
                    if (match) {
                        const extractedPageId = match[1];
                        saveUrl = `/admin/pages/${extractedPageId}/save-grapesjs`;
                    }
                }
            }
            
            if (!saveUrl) {
                alert('Save URL not found. Please refresh the page and try again.');
                return;
            }
            
            try {
                const response = await fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ html, css }),
                    credentials: 'same-origin'
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    this.showSaveStatus('success', result.message || 'Page builder content saved!');
                    
                    // Update the Filament form state with the saved data
                    // This ensures that when the user clicks "Save Changes" in Filament,
                    // it will have the latest GrapesJS data
                    const savedData = {
                        html: html,
                        css: css,
                        data: this.editor.getProjectData(),
                        last_updated: new Date().toISOString(),
                        saved_via_ajax: true
                    };
                    
                    // Update Filament's form state
                    this.updateFilamentFormState();
                    
                    // Also ensure the form field is properly updated
                    const form = this.wrapper.closest('form');
                    if (form) {
                        // Dispatch a custom event for any listeners
                        const syncEvent = new CustomEvent('grapesjs-sync', {
                            detail: {
                                field: this.options.statePath,
                                data: savedData
                            },
                            bubbles: true
                        });
                        
                        form.dispatchEvent(syncEvent);
                        console.log('Dispatched grapesjs-sync event after AJAX save:', savedData);
                    }
                } else {
                    this.showSaveStatus('error', 'Save failed: ' + (result.message || result.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Save error:', error);
                this.showSaveStatus('error', 'Save error: ' + error.message);
            }
        } else {
            // Frontend: POST to saveUrl
            const html = this.editor.getHtml();
            const css = this.editor.getCss();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                alert('CSRF token not found. Please refresh the page and try again.');
                return;
            }
            try {
                const response = await fetch(this.options.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ html, css }),
                    credentials: 'same-origin'
                });
                const result = await response.json();
                if (!(response.ok && result.success)) {
                    alert('Save failed: ' + (result.message || result.error));
                }
            } catch (error) {
                alert('Save error: ' + error.message);
            }
        }
    }

    setupEventListeners() {
        // Fullscreen toggle
        if (this.fullscreenBtn) {
            this.fullscreenBtn.addEventListener('click', () => {
                this.toggleFullscreen();
            });
        }
        
        // Save button (backend)
        if (this.options.mode === 'backend' && this.wrapper) {
            const saveBtn = this.wrapper.querySelector('.grapesjs-save-btn');
            
            if (saveBtn) {
                const editorElement = document.getElementById(this.options.containerId);
                const saveUrl = editorElement?.dataset.saveUrl;
                
                // Always enable the save button in backend mode
                saveBtn.disabled = false;
                saveBtn.style.opacity = '1';
                saveBtn.style.cursor = 'pointer';
                saveBtn.title = 'Save page builder content';
                
                saveBtn.addEventListener('click', () => {
                    this.saveContent();
                });
            }
            
            // Enhanced form submission handling for Filament
            this.setupFilamentFormSubmission();
        }
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.exitFullscreen();
            }
        });
    }

    setupFilamentFormSubmission() {
        const form = this.wrapper.closest('form');
        if (!form) return;

        console.log('Setting up Filament form submission handlers...');

        // Method 1: Listen for form submit events and sync data
        form.addEventListener('submit', (e) => {
            console.log('Form submit event detected - syncing data...');
            this.syncToFormBeforeSubmit();
        });

        // Method 2: Listen for Filament-specific events
        form.addEventListener('filament:submit', (e) => {
            console.log('Filament submit event detected');
            this.syncToFormBeforeSubmit();
        });

        // Method 3: Listen for button clicks that might submit the form - MORE AGGRESSIVE
        document.addEventListener('mousedown', (e) => {
            const target = e.target;
            if (target && (
                target.type === 'submit' || 
                target.classList.contains('fi-btn--primary') ||
                target.closest('.fi-btn--primary') ||
                target.textContent?.toLowerCase().includes('save') ||
                target.textContent?.toLowerCase().includes('create') ||
                target.textContent?.toLowerCase().includes('update')
            )) {
                // Check if this button is in the same form
                const buttonForm = target.closest('form');
                if (buttonForm === form) {
                    console.log('Save button mousedown detected, syncing content immediately...');
                    // Sync immediately on mousedown (before the click event)
                    this.syncToFormBeforeSubmit();
                }
            }
        });

        // Method 4: Listen for Filament's internal form submission
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-loading') {
                    const loadingElement = mutation.target;
                    if (loadingElement.getAttribute('data-loading') === 'true') {
                        console.log('Filament loading state detected, syncing content...');
                        this.syncToFormBeforeSubmit();
                    }
                }
            });
        });

        // Observe the form for loading state changes
        observer.observe(form, {
            attributes: true,
            attributeFilter: ['data-loading']
        });

        // Method 5: Use Filament's internal events if available
        if (window.Filament) {
            window.Filament.on('form-submit', () => {
                console.log('Filament form-submit event detected');
                this.syncToFormBeforeSubmit();
            });
        }

        // Method 6: Periodic sync to ensure data is always up to date
        setInterval(() => {
            if (this.editor && this.options.mode === 'backend') {
                this.updateFilamentFormState();
            }
        }, 5000); // Sync every 5 seconds
        
        // Method 6.5: Periodic check for empty editor during form submission
        setInterval(() => {
            if (this.editor && this.isFormSubmitting) {
                this.checkAndRestoreContent();
            }
        }, 1000); // Check every second during form submission

        // Method 7: Listen for beforeunload events (when user tries to leave page)
        window.addEventListener('beforeunload', (e) => {
            if (this.editor && this.options.mode === 'backend') {
                console.log('Page unload detected, syncing content...');
                this.syncToFormBeforeSubmit();
            }
        });

        // Method 8: Listen for Livewire events if available
        if (window.Livewire) {
            window.Livewire.on('form-submit', () => {
                console.log('Livewire form-submit event detected');
                this.syncToFormBeforeSubmit();
            });
        }

        // Method 9: Override Filament's form submission if possible
        if (window.Filament && window.Filament.forms) {
            const originalSubmit = window.Filament.forms.submit;
            if (originalSubmit) {
                window.Filament.forms.submit = (...args) => {
                    console.log('Filament forms.submit intercepted');
                    this.syncToFormBeforeSubmit();
                    return originalSubmit.apply(this, args);
                };
            }
        }
        
        // Method 10: Detect when the editor element is about to be removed/replaced
        const editorObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    mutation.removedNodes.forEach((node) => {
                        if (node.id === this.options.containerId || 
                            node.querySelector && node.querySelector(`#${this.options.containerId}`)) {
                            console.log('WARNING: Editor element is being removed! This will cause the editor to go blank.');
                            this.syncToFormBeforeSubmit();
                        }
                    });
                }
            });
        });
        
        // Observe the parent of the editor for changes
        const editorParent = form.parentElement;
        if (editorParent) {
            editorObserver.observe(editorParent, {
                childList: true,
                subtree: true
            });
        }
        
        // Method 11: Listen for page visibility changes (when user switches tabs)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && this.editor && this.options.mode === 'backend') {
                console.log('Page hidden, syncing content...');
                this.syncToFormBeforeSubmit();
            }
        });
    }

    toggleFullscreen() {
        if (!this.wrapper) return;
        const fullscreenIcon = this.fullscreenBtn?.querySelector('.fullscreen-icon');
        const exitIcon = this.fullscreenBtn?.querySelector('.exit-fullscreen-icon');
        const editorDiv = this.wrapper.querySelector('.grapesjs-editor');
        if (this.wrapper.classList.contains('fullscreen')) {
            this.exitFullscreen();
        } else {
            this.enterFullscreen();
        }
    }
    enterFullscreen() {
        if (!this.wrapper) return;
        const fullscreenIcon = this.fullscreenBtn?.querySelector('.fullscreen-icon');
        const exitIcon = this.fullscreenBtn?.querySelector('.exit-fullscreen-icon');
        const editorDiv = this.wrapper.querySelector('.grapesjs-editor');
        this.wrapper.classList.add('fullscreen');
        if (fullscreenIcon) fullscreenIcon.style.display = 'none';
        if (exitIcon) exitIcon.style.display = 'block';
        if (this.fullscreenBtn) this.fullscreenBtn.title = 'Exit Fullscreen';
        if (editorDiv) editorDiv.style.height = 'calc(100vh - 120px)';
        document.body.style.overflow = 'hidden';
    }
    exitFullscreen() {
        if (!this.wrapper) return;
        const fullscreenIcon = this.fullscreenBtn?.querySelector('.fullscreen-icon');
        const exitIcon = this.fullscreenBtn?.querySelector('.exit-fullscreen-icon');
        const editorDiv = this.wrapper.querySelector('.grapesjs-editor');
        this.wrapper.classList.remove('fullscreen');
        if (fullscreenIcon) fullscreenIcon.style.display = 'block';
        if (exitIcon) exitIcon.style.display = 'none';
        if (this.fullscreenBtn) this.fullscreenBtn.title = 'Toggle Fullscreen Mode (Press Escape to exit)';
        if (editorDiv) editorDiv.style.height = editorDiv.dataset.height || '600px';
        document.body.style.overflow = '';
    }
    destroy() {
        if (this.editor) {
            this.editor.destroy();
        }
    }
    
    showSaveStatus(type, message) {
        console.log('Save status:', type, message);
        
        // For backend, show a temporary notification
        if (this.options.mode === 'backend') {
            // Create a temporary notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 1000000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                transition: all 0.3s ease;
                ${type === 'success' ? 'background: #10b981;' : 'background: #ef4444;'}
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        } else {
            // For frontend, use alert as fallback
            alert(message);
        }
    }
}

// Export globally
window.LaraGrapeGrapesJsEditor = LaraGrapeGrapesJsEditor;

function injectStylesIntoGrapesJsIframe(editor, stylesArray) {
    const iframe = editor.Canvas.getFrameEl();
    if (!iframe) return;
    const head = iframe.contentDocument.head;
    // Remove any previously injected styles to avoid duplicates
    Array.from(head.querySelectorAll('[data-grapey-injected]')).forEach(el => el.remove());
    stylesArray.forEach(style => {
        let el;
        if (style.startsWith('<style')) {
            el = document.createElement('style');
            el.setAttribute('data-grapey-injected', 'true');
            el.innerHTML = style.replace(/^<style[^>]*>|<\/style>$/g, '');
        } else if (style.endsWith('.css')) {
            el = document.createElement('link');
            el.setAttribute('data-grapey-injected', 'true');
            el.rel = 'stylesheet';
            el.href = style;
        }
        if (el) head.appendChild(el);
    });
}

// Global function to sync GrapesJS data before form submission
function syncGrapesJsData() {
    console.log('Global syncGrapesJsData called');
    // Find all GrapesJS editors on the page
    const editors = document.querySelectorAll('.grapesjs-editor');
    editors.forEach(editor => {
        const editorInstance = editor.laraGrapeEditor;
        if (editorInstance && typeof editorInstance.syncToFormBeforeSubmit === 'function') {
            editorInstance.syncToFormBeforeSubmit();
        }
    });
    
    // Also try to find editors by wrapper
    const wrappers = document.querySelectorAll('.grapejs-editor-wrapper');
    wrappers.forEach(wrapper => {
        const editorInstance = wrapper.laraGrapeEditor;
        if (editorInstance && typeof editorInstance.syncToFormBeforeSubmit === 'function') {
            editorInstance.syncToFormBeforeSubmit();
        }
    });
}

// Make it globally available
window.syncGrapesJsData = syncGrapesJsData;

// Also listen for the custom grapesjs-sync event
document.addEventListener('grapesjs-sync', function(event) {
    console.log('Received grapesjs-sync event:', event.detail);
    const { field, data } = event.detail;
    
    // Log the event for debugging purposes
    console.log('GrapesJS sync event received for field:', field, 'with data:', data);
});
