<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ $page->meta_title ?: $page->title }} - {{ config('app.name') }}</title>
    
    <!-- SEO Meta Tags -->
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $page->meta_title ?: $page->title }}">
    @if($page->meta_description)
        <meta property="og:description" content="{{ $page->meta_description }}">
    @endif
    @if($page->featured_image)
        <meta property="og:image" content="{{ Storage::url($page->featured_image) }}">
    @endif
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        /* LaralGrape Default Styles */
        .laralgrape-container {
            min-height: 100vh;
        }
        
        /* Responsive utilities */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Typography improvements */
        h1, h2, h3, h4, h5, h6 {
            line-height: 1.2;
            margin-bottom: 0.5em;
        }
        
        p {
            line-height: 1.6;
            margin-bottom: 1em;
        }
        
        /* Button improvements */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            border: none;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        /* Card improvements */
        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Section spacing */
        section {
            padding: 2rem 0;
        }
        
        @media (min-width: 768px) {
            section {
                padding: 4rem 0;
            }
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">
    <!-- GrapesJS Edit Bar -->
    <div id="grapejs-edit-bar" style="background:#1e293b;color:white;padding:8px 0;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);font-size:15px;">
        <span>Edit this page with GrapesJS</span>
        <button id="grapejs-edit-btn" style="margin-left:16px;background:#3b82f6;color:white;padding:6px 18px;border-radius:6px;border:none;cursor:pointer;font-weight:500;">Edit</button>
        <button id="grapejs-save-btn" style="display:none;margin-left:8px;background:#10b981;color:white;padding:6px 18px;border-radius:6px;border:none;cursor:pointer;font-weight:500;">Save</button>
        <button id="grapejs-exit-btn" style="display:none;margin-left:8px;background:#ef4444;color:white;padding:6px 18px;border-radius:6px;border:none;cursor:pointer;font-weight:500;">Exit</button>
    </div>
    <style>
        body .grapejs-nav-margin { margin-top: 48px; }
    </style>

    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b @if(auth()->check()) grapejs-nav-margin @endif">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">
                        {{ config('app.name', 'LaralGrape') }}
                    </a>
                </div>
                <div class="hidden md:flex space-x-6">
                    @php
                        $menuPages = \App\Models\Page::published()->inMenu()->get();
                    @endphp
                    
                    @foreach($menuPages as $menuPage)
                        <a href="{{ route('page.show', $menuPage->slug) }}" 
                           class="text-gray-600 hover:text-gray-900 transition duration-300">
                            {{ $menuPage->title }}
                        </a>
                    @endforeach
                    
                    <a href="{{ route('filament.admin.auth.login') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                        Admin
                    </a>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-600 hover:text-gray-900" onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                @foreach($menuPages as $menuPage)
                    <a href="{{ route('page.show', $menuPage->slug) }}" 
                       class="block py-2 text-gray-600 hover:text-gray-900">
                        {{ $menuPage->title }}
                    </a>
                @endforeach
                <a href="{{ route('filament.admin.auth.login') }}" 
                   class="block py-2 text-blue-600 hover:text-blue-800">
                    Admin Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="laralgrape-container">
        <!-- Page Content -->
        <div class="page-content">
            @php
                $blocks = $page->grapesjs_data ?? [];
            @endphp

            @foreach($blocks as $block)
                @includeIf('blocks.' . $block['type'], $block['props'] ?? [])
            @endforeach
        </div>
        @if(auth()->check())
            <!-- GrapesJS Editor Container (hidden by default) -->
            <div id="grapejs-frontend-editor" style="display:none;"></div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'LaralGrape') }}. Built with Laravel, GrapesJS & Filament.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });
    </script>

    @if(auth()->check())
        <!-- GrapesJS Edit Bar -->
        <div id="grapejs-edit-bar" style="background:#1e293b;color:white;padding:8px 0;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);font-size:15px;">
            <span>Edit this page with GrapesJS</span>
            <button id="grapejs-edit-btn" style="margin-left:16px;background:#3b82f6;color:white;padding:6px 18px;border-radius:6px;border:none;cursor:pointer;font-weight:500;">Edit</button>
            <button id="grapejs-save-btn" style="display:none;margin-left:8px;background:#10b981;color:white;padding:6px 18px;border-radius:6px;border:none;cursor:pointer;font-weight:500;">Save</button>
            <button id="grapejs-exit-btn" style="display:none;margin-left:8px;background:#ef4444;color:white;padding:6px 18px;border-radius:6px;border:none;cursor:pointer;font-weight:500;">Exit</button>
        </div>
        <style>
            body .grapejs-nav-margin { margin-top: 48px; }
            #grapejs-frontend-editor { min-height: 400px; }
        </style>
        <!-- Load GrapesJS CSS/JS -->
        <link rel="stylesheet" href="https://unpkg.com/grapesjs@0.22.8/dist/css/grapes.min.css">
        <script src="https://unpkg.com/grapesjs@0.22.8/dist/grapes.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editBar = document.getElementById('grapejs-edit-bar');
                const editBtn = document.getElementById('grapejs-edit-btn');
                const saveBtn = document.getElementById('grapejs-save-btn');
                const exitBtn = document.getElementById('grapejs-exit-btn');
                const editorContainer = document.getElementById('grapejs-frontend-editor');
                const pageContent = document.querySelector('.page-content');
                let grapejsEditor = null;
                let originalScroll = 0;
            
                editBtn.onclick = function() {
                    pageContent.style.display = 'none';
                    editorContainer.style.display = 'block';
                    editorContainer.style.minHeight = '400px';
                    saveBtn.style.display = 'inline-block';
                    exitBtn.style.display = 'inline-block';
                    editBtn.style.display = 'none';
                    editBar.style.position = 'static';
                    originalScroll = window.scrollY;
                    document.body.style.overflow = '';
                    if (!grapejsEditor) {
                        grapejsEditor = grapesjs.init({
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
                                blocks: [
                                    {
                                        id: 'hero',
                                        label: 'Hero',
                                        content: '<section class="hero bg-gradient-to-r from-blue-500 to-purple-600 text-white py-20"><div class="container mx-auto px-4 text-center"><h1 class="text-5xl font-bold mb-4">Welcome to LaralGrape</h1><p class="text-xl mb-8">Build amazing websites with our visual editor</p><button class="bg-white text-blue-600 font-bold py-3 px-6 rounded-lg hover:bg-gray-100 transition duration-300">Get Started</button></div></section>'
                                    },
                                    {
                                        id: 'section',
                                        label: 'Section',
                                        content: '<section class="py-16 bg-gray-50"><div class="container mx-auto px-4"><h2 class="text-3xl font-bold text-center mb-8">Section Title</h2><p class="text-lg text-center text-gray-600">Add your content here</p></div></section>'
                                    },
                                    {
                                        id: 'columns',
                                        label: 'Columns',
                                        content: '<div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8"><div class="bg-white p-6 rounded-lg shadow-lg"><h3 class="text-xl font-bold mb-4">Column 1</h3><p class="text-gray-600">Add your content here</p></div><div class="bg-white p-6 rounded-lg shadow-lg"><h3 class="text-xl font-bold mb-4">Column 2</h3><p class="text-gray-600">Add your content here</p></div></div>'
                                    },
                                    {
                                        id: 'card',
                                        label: 'Card',
                                        content: '<div class="max-w-sm mx-auto bg-white rounded-xl shadow-md overflow-hidden"><img class="w-full h-48 object-cover" src="https://via.placeholder.com/400x200" alt="Card image"><div class="p-6"><h3 class="text-xl font-bold mb-2">Card Title</h3><p class="text-gray-600 mb-4">Card description goes here</p><button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Learn More</button></div></div>'
                                    }
                                ]
                            }
                        });
                        grapejsEditor.setComponents(@json($page->grapesjs_data['html'] ?? ''));
                        grapejsEditor.setStyle(@json($page->grapesjs_data['css'] ?? ''));
                    }
                };
                exitBtn.onclick = function() {
                    pageContent.style.display = '';
                    editorContainer.style.display = 'none';
                    saveBtn.style.display = 'none';
                    exitBtn.style.display = 'none';
                    editBtn.style.display = 'inline-block';
                    document.body.style.overflow = '';
                    window.scrollTo(0, originalScroll);
                };
                saveBtn.onclick = function() {
                    const html = grapejsEditor.getHtml();
                    const css = grapejsEditor.getCss();
                    saveBtn.innerText = 'Saving...';
                    fetch(window.location.pathname + '/save-grapesjs', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ html, css })
                    })
                    .then(res => res.json())
                    .then(data => {
                        saveBtn.innerText = 'Saved!';
                        setTimeout(() => saveBtn.innerText = 'Save', 1200);
                    })
                    .catch(() => {
                        saveBtn.innerText = 'Error!';
                        setTimeout(() => saveBtn.innerText = 'Save', 1200);
                    });
                };
            });
            </script>
    @endif
</body>
</html>
