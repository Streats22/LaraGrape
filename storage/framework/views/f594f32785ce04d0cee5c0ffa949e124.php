<?php
    $siteSettings = app(\App\Services\SiteSettingsService::class);
    $headerSettings = $siteSettings->getHeaderSettings();
    $generalSettings = $siteSettings->getGeneralSettings();
?>

<header class="site-header bg-white shadow-sm border-b border-gray-200" 
        style="background-color: <?php echo e($headerSettings['background_color']); ?>; color: <?php echo e($headerSettings['text_color']); ?>; <?php echo e($headerSettings['sticky'] ? 'position: sticky; top: 0; z-index: 50;' : ''); ?>">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-2">
                    <?php if($headerSettings['logo_image']): ?>
                        <img src="<?php echo e(Storage::url($headerSettings['logo_image'])); ?>" 
                             alt="<?php echo e($generalSettings['site_name']); ?>" 
                             class="h-8 w-auto">
                    <?php else: ?>
                        <div class="text-2xl font-bold text-purple-600">
                            <?php echo e($headerSettings['logo_text']); ?>

                        </div>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <?php $__currentLoopData = \App\Models\Page::inMenu()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuPage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('page.show', $menuPage->slug)); ?>" 
                       class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <?php echo e($menuPage->title); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <!-- Right side items -->
            <div class="flex items-center space-x-4">
                
                <!-- Search (if enabled) -->
                <?php if($headerSettings['show_search']): ?>
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search..." 
                               class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Admin Link (if authenticated) -->
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('filament.admin.pages.dashboard')); ?>" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Admin
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="text-gray-700 hover:text-purple-600 focus:outline-none focus:text-purple-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="md:hidden border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <?php $__currentLoopData = \App\Models\Page::inMenu()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuPage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('page.show', $menuPage->slug)); ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-gray-50 transition-colors duration-200">
                    <?php echo e($menuPage->title); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('filament.admin.pages.dashboard')); ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium bg-purple-600 text-white hover:bg-purple-700 transition-colors duration-200">
                    Admin Panel
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<?php if($headerSettings['custom_css']): ?>
<style>
<?php echo $headerSettings['custom_css']; ?>

</style>
<?php endif; ?> <?php /**PATH /Users/robinschoenmaker/StreatsDesign/LaraGrape/resources/views/components/layout/header.blade.php ENDPATH**/ ?>