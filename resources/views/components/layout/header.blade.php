<header class="bg-white shadow-sm border-b @if(auth()->check()) grapejs-nav-margin @endif">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">
                    {{ config('app.name', 'LaralGrape') }}
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-6">
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
            </nav>
            
            <!-- Mobile menu button -->
            <button 
                type="button" 
                class="md:hidden text-gray-600 hover:text-gray-900"
                @click="mobileMenuOpen = !mobileMenuOpen"
                aria-label="Toggle mobile menu"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Navigation -->
        <nav 
            class="md:hidden pb-4"
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            style="display: none;"
        >
            @foreach($menuPages as $menuPage)
                <a href="{{ route('page.show', $menuPage->slug) }}" 
                   class="block py-2 text-gray-600 hover:text-gray-900 transition duration-300">
                    {{ $menuPage->title }}
                </a>
            @endforeach
            <a href="{{ route('filament.admin.auth.login') }}" 
               class="block py-2 text-blue-600 hover:text-blue-800 transition duration-300">
                Admin Login
            </a>
        </nav>
    </div>
</header> 