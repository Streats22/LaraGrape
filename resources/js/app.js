import './bootstrap';
import Alpine from 'alpinejs';

// Register Alpine components
Alpine.data('siteLayout', () => ({
    mobileMenuOpen: false,
    
    init() {
        // Close mobile menu when clicking outside
        document.addEventListener('click', (event) => {
            const menu = this.$el.querySelector('[x-show="mobileMenuOpen"]');
            const button = event.target.closest('button');
            
            if (menu && !menu.contains(event.target) && !button) {
                this.mobileMenuOpen = false;
            }
        });
    }
}));

Alpine.data('grapejsEditBar', () => ({
    isEditing: false,
    isSaving: false,
    originalScroll: 0,
    grapejsEditor: null,
    saveStatus: '', // 'success', 'error', or ''
    
    startEditing() {
        this.isEditing = true;
        this.originalScroll = window.scrollY;
        
        // Hide page content and show editor
        const pageContent = document.querySelector('.page-content');
        const editorWrapper = document.querySelector('.grapejs-editor-wrapper');
        
        if (pageContent) pageContent.style.display = 'none';
        if (editorWrapper) editorWrapper.style.display = 'block';
        
        // Initialize GrapesJS if not already done
        if (!this.grapejsEditor && typeof grapesjs !== 'undefined') {
            this.initializeGrapesJS();
        }
    },
    
    exitEditing() {
        this.isEditing = false;
        this.saveStatus = '';
        
        // Show page content and hide editor
        const pageContent = document.querySelector('.page-content');
        const editorWrapper = document.querySelector('.grapejs-editor-wrapper');
        
        if (pageContent) pageContent.style.display = '';
        if (editorWrapper) editorWrapper.style.display = 'none';
        
        // Restore scroll position
        window.scrollTo(0, this.originalScroll);
    },
    
    async saveContent() {
        if (!this.grapejsEditor) {
            console.error('GrapesJS editor not initialized');
            this.showSaveStatus('error', 'Editor not initialized');
            return;
        }
        
        this.isSaving = true;
        this.saveStatus = '';
        
        try {
            const html = this.grapejsEditor.getHtml();
            const css = this.grapejsEditor.getCss();
            
            console.log('Saving content:', { html, css });
            console.log('Current URL:', window.location.pathname);
            
            // Get fresh CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page and try again.');
            }
            
            console.log('CSRF Token:', csrfToken);
            
            const saveUrl = window.location.pathname + '/save-grapesjs';
            console.log('Save URL:', saveUrl);
            
            const response = await fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ html, css }),
                credentials: 'same-origin' // Include cookies for authentication
            });
            
            console.log('Response status:', response.status);
            console.log('Response headers:', Object.fromEntries(response.headers.entries()));
            
            if (response.status === 419) {
                throw new Error('CSRF token expired. Please refresh the page and try again.');
            }
            
            if (response.status === 401) {
                throw new Error('Authentication required. Please log in to save changes.');
            }
            
            if (response.status === 404) {
                throw new Error('Page not found.');
            }
            
            const result = await response.json();
            console.log('Response data:', result);
            
            if (response.ok && result.success) {
                this.showSaveStatus('success', result.message || 'Content saved successfully!');
                
                // Update the page content to reflect the saved changes
                const pageContent = document.querySelector('.page-content');
                if (pageContent) {
                    pageContent.innerHTML = html;
                    if (css) {
                        // Add CSS to the page
                        const styleId = 'grapesjs-saved-styles';
                        let styleElement = document.getElementById(styleId);
                        if (!styleElement) {
                            styleElement = document.createElement('style');
                            styleElement.id = styleId;
                            document.head.appendChild(styleElement);
                        }
                        styleElement.textContent = css;
                    }
                }
            } else {
                throw new Error(result.error || result.message || 'Save failed');
            }
        } catch (error) {
            console.error('Save error:', error);
            this.showSaveStatus('error', error.message || 'Failed to save content');
        } finally {
            this.isSaving = false;
        }
    },
    
    showSaveStatus(type, message) {
        this.saveStatus = type;
        
        // Show status message
        const statusElement = this.$el.querySelector('.save-status');
        if (statusElement) {
            statusElement.textContent = message;
            statusElement.className = `save-status ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
            statusElement.style.display = 'block';
            
            setTimeout(() => {
                statusElement.style.display = 'none';
                this.saveStatus = '';
            }, 3000);
        }
    },
    
    initializeGrapesJS() {
        // Load blocks from PHP
        const blocks = window.grapesjsBlocks || [];
        
        console.log('Initializing GrapesJS with blocks:', blocks);
        
        this.grapejsEditor = grapesjs.init({
            container: '#grapejs-frontend-editor',
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
                appendTo: '#grapejs-frontend-editor',
                blocks: blocks.map(block => ({
                    ...block,
                    media: block.icon ? `<i class="${block.icon}"></i>` : '<i class="fas fa-cube"></i>'
                }))
            },
            // Style manager configuration
            styleManager: {
                sectors: [
                    {
                        name: 'General',
                        open: false,
                        buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom']
                    },
                    {
                        name: 'Dimension',
                        open: false,
                        buildProps: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding']
                    },
                    {
                        name: 'Typography',
                        open: false,
                        buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-decoration', 'text-shadow']
                    },
                    {
                        name: 'Decorations',
                        open: false,
                        buildProps: ['border-radius', 'border', 'box-shadow', 'background']
                    },
                    {
                        name: 'Extra',
                        open: false,
                        buildProps: ['opacity', 'transition', 'transform']
                    }
                ]
            },
            // Layer manager configuration
            layerManager: {
                appendTo: null
            },
            // Panel manager configuration
            panels: {
                defaults: [
                    {
                        id: 'basic-actions',
                        el: '.panel__basic-actions',
                        buttons: [
                            {
                                id: 'visibility',
                                active: true,
                                className: 'btn-toggle-borders',
                                label: '<u>B</u>',
                                command: 'sw-visibility',
                            }
                        ],
                    }
                ]
            },
            // Device manager configuration
            deviceManager: {
                devices: [
                    {
                        name: 'Desktop',
                        width: '',
                    },
                    {
                        name: 'Tablet',
                        width: '768px',
                        widthMedia: '992px',
                    },
                    {
                        name: 'Mobile',
                        width: '320px',
                        widthMedia: '480px',
                    }
                ]
            }
        });
        
        // Load existing content if available
        const pageData = window.pageGrapesjsData;
        if (pageData && pageData.html) {
            console.log('Loading existing content:', pageData);
            this.grapejsEditor.setComponents(pageData.html);
        }
        if (pageData && pageData.css) {
            this.grapejsEditor.setStyle(pageData.css);
        }
        
        console.log('GrapesJS initialized successfully');
        
        // Open the block manager panel by default
        if (this.grapejsEditor.Panels.getButton('views', 'open-blocks')) {
            this.grapejsEditor.Panels.getButton('views', 'open-blocks').set('active', true);
        }
    }
}));

// Start Alpine
Alpine.start();

// Make Alpine available globally
window.Alpine = Alpine;
