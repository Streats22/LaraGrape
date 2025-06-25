import grapesjs from 'grapesjs';

export default function grapesJsEditor({
    state,
    isDisabled,
    height = '600px'
}) {
    return {
        state,
        editor: null,
        
        init() {
            this.initEditor();
        },
        
        initEditor() {
            // Initialize GrapesJS
            this.editor = grapesjs.init({
                container: this.$refs.editor,
                height: height,
                width: 'auto',
                fromElement: false,
                showOffsets: true,
                noticeOnUnload: false,
                storageManager: false,
                
                // Canvas settings
                canvas: {
                    styles: [
                        'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css'
                    ]
                },
                
                // Block Manager
                blockManager: {
                    appendTo: '.blocks-container',
                    blocks: [
                        {
                            id: 'section',
                            label: 'Section',
                            attributes: { class: 'gjs-block-section' },
                            content: '<section class="py-16 bg-gray-50"><div class="container mx-auto px-4"><h2 class="text-3xl font-bold text-center mb-8">Section Title</h2><p class="text-lg text-center text-gray-600">Add your content here</p></div></section>'
                        },
                        {
                            id: 'text',
                            label: 'Text',
                            content: '<div class="text-component">Insert your text here</div>',
                        },
                        {
                            id: 'image',
                            label: 'Image',
                            select: true,
                            content: { type: 'image' },
                            activate: true,
                        },
                        {
                            id: 'button',
                            label: 'Button',
                            content: '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Click me</button>',
                        },
                        {
                            id: 'hero',
                            label: 'Hero Section',
                            content: '<section class="hero bg-gradient-to-r from-blue-500 to-purple-600 text-white py-20"><div class="container mx-auto px-4 text-center"><h1 class="text-5xl font-bold mb-4">Welcome to LaralGrape</h1><p class="text-xl mb-8">Build amazing websites with our visual editor</p><button class="bg-white text-blue-600 font-bold py-3 px-6 rounded-lg hover:bg-gray-100 transition duration-300">Get Started</button></div></section>'
                        },
                        {
                            id: 'columns',
                            label: '2 Columns',
                            content: '<div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8"><div class="bg-white p-6 rounded-lg shadow-lg"><h3 class="text-xl font-bold mb-4">Column 1</h3><p class="text-gray-600">Add your content here</p></div><div class="bg-white p-6 rounded-lg shadow-lg"><h3 class="text-xl font-bold mb-4">Column 2</h3><p class="text-gray-600">Add your content here</p></div></div>'
                        },
                        {
                            id: 'card',
                            label: 'Card',
                            content: '<div class="max-w-sm mx-auto bg-white rounded-xl shadow-md overflow-hidden"><img class="w-full h-48 object-cover" src="https://via.placeholder.com/400x200" alt="Card image"><div class="p-6"><h3 class="text-xl font-bold mb-2">Card Title</h3><p class="text-gray-600 mb-4">Card description goes here</p><button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Learn More</button></div></div>'
                        }
                    ]
                },
                
                // Style Manager
                styleManager: {
                    appendTo: '.styles-container',
                    sectors: [{
                        name: 'Dimension',
                        open: false,
                        buildProps: ['width', 'min-height', 'padding'],
                        properties: [{
                            type: 'integer',
                            name: 'The width',
                            property: 'width',
                            units: ['px', '%'],
                            defaults: 'auto',
                            min: 0,
                        }]
                    }, {
                        name: 'Extra',
                        open: false,
                        buildProps: ['background-color', 'box-shadow', 'custom-prop'],
                        properties: [{
                            id: 'custom-prop',
                            name: 'Custom Label',
                            property: 'font-size',
                            type: 'select',
                            defaults: '32px',
                            options: [
                                { value: '12px', name: 'Tiny' },
                                { value: '18px', name: 'Medium' },
                                { value: '32px', name: 'Big' },
                            ],
                        }]
                    }]
                },
                
                // Device Manager
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
                            widthMedia: '768px',
                        },
                    ]
                }
            });
            
            // Load existing content if available
            if (this.state) {
                try {
                    const data = typeof this.state === 'string' ? JSON.parse(this.state) : this.state;
                    if (data.html) {
                        this.editor.setComponents(data.html);
                    }
                    if (data.css) {
                        this.editor.setStyle(data.css);
                    }
                } catch (e) {
                    console.error('Error loading GrapesJS data:', e);
                }
            }
            
            // Listen for changes and update state
            this.editor.on('change:changedComponent change:changedStyle', () => {
                this.updateState();
            });
            
            // Disable editor if needed
            if (isDisabled) {
                this.editor.Commands.run('core:canvas-clear');
            }
            
            // After initializing the editor (this.editor = grapesjs.init({...}))
            this.editor.Panels.addButton('options', [{
                id: 'save-db',
                className: 'fa fa-save',
                label: 'Save',
                command: () => {
                    const html = this.editor.getHtml();
                    const css = this.editor.getCss();
                    // If using Alpine:
                    this.state = JSON.stringify({ html, css });
                    // If using Livewire, you may need to call $wire.set('grapesjs_data', ...)
                    alert('Content ready to be saved! Now click the Filament Save button.');
                }
            }]);
        },
        
        updateState() {
            if (!this.editor) return;
            
            const html = this.editor.getHtml();
            const css = this.editor.getCss();
            const data = this.editor.getProjectData();
            
            this.state = JSON.stringify({
                html: html,
                css: css,
                data: data
            });
        },
        
        destroy() {
            if (this.editor) {
                this.editor.destroy();
                this.editor = null;
            }
        }
    }
}
