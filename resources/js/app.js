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
    
    startEditing() {
        this.isEditing = true;
        this.originalScroll = window.scrollY;
        
        // Hide page content and show editor
        const pageContent = document.querySelector('.page-content');
        const editorContainer = document.getElementById('grapejs-frontend-editor');
        
        if (pageContent) pageContent.style.display = 'none';
        if (editorContainer) {
            editorContainer.style.display = 'block';
            editorContainer.style.minHeight = '400px';
        }
        
        // Initialize GrapesJS if not already done
        if (!this.grapejsEditor && typeof grapesjs !== 'undefined') {
            this.initializeGrapesJS();
        }
    },
    
    exitEditing() {
        this.isEditing = false;
        
        // Show page content and hide editor
        const pageContent = document.querySelector('.page-content');
        const editorContainer = document.getElementById('grapejs-frontend-editor');
        
        if (pageContent) pageContent.style.display = '';
        if (editorContainer) editorContainer.style.display = 'none';
        
        // Restore scroll position
        window.scrollTo(0, this.originalScroll);
    },
    
    async saveContent() {
        if (!this.grapejsEditor) return;
        
        this.isSaving = true;
        
        try {
            const html = this.grapejsEditor.getHtml();
            const css = this.grapejsEditor.getCss();
            
            const response = await fetch(window.location.pathname + '/save-grapesjs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ html, css })
            });
            
            if (response.ok) {
                // Show success feedback
                const saveBtn = this.$el.querySelector('.grapejs-btn-success');
                if (saveBtn) {
                    const originalText = saveBtn.textContent;
                    saveBtn.textContent = 'Saved!';
                    setTimeout(() => {
                        saveBtn.textContent = originalText;
                    }, 1200);
                }
            } else {
                throw new Error('Save failed');
            }
        } catch (error) {
            console.error('Save error:', error);
            // Show error feedback
            const saveBtn = this.$el.querySelector('.grapejs-btn-success');
            if (saveBtn) {
                const originalText = saveBtn.textContent;
                saveBtn.textContent = 'Error!';
                setTimeout(() => {
                    saveBtn.textContent = originalText;
                }, 1200);
            }
        } finally {
            this.isSaving = false;
        }
    },
    
    initializeGrapesJS() {
        // Load blocks from PHP
        const blocks = window.grapesjsBlocks || [];
        
        this.grapejsEditor = grapesjs.init({
            container: '#grapejs-frontend-editor',
            height: '70vh',
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
                appendTo: null,
                blocks: blocks
            }
        });
        
        // Load existing content if available
        const pageData = window.pageGrapesjsData;
        if (pageData && pageData.html) {
            this.grapejsEditor.setComponents(pageData.html);
        }
        if (pageData && pageData.css) {
            this.grapejsEditor.setStyle(pageData.css);
        }
    }
}));

// Start Alpine
Alpine.start();

// Make Alpine available globally
window.Alpine = Alpine;
