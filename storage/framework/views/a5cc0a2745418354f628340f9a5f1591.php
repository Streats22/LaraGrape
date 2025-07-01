<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo e($page->meta_title ?: $page->title); ?> - <?php echo e(config('app.name')); ?></title>
    
    <!-- SEO Meta Tags -->
    <?php if($page->meta_description): ?>
        <meta name="description" content="<?php echo e($page->meta_description); ?>">
    <?php endif; ?>
    
    <?php if($page->meta_keywords): ?>
        <meta name="keywords" content="<?php echo e($page->meta_keywords); ?>">
    <?php endif; ?>
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo e($page->meta_title ?: $page->title); ?>">
    <?php if($page->meta_description): ?>
        <meta property="og:description" content="<?php echo e($page->meta_description); ?>">
    <?php endif; ?>
    <?php if($page->featured_image): ?>
        <meta property="og:image" content="<?php echo e(Storage::url($page->featured_image)); ?>">
    <?php endif; ?>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome for GrapesJS icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- GrapesJS CSS for frontend editor -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/grapesjs@0.22.8/dist/css/grapes.min.css">
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/app.css', 
        'resources/js/app.js'
    ]); ?>
    
    <style>
        /* Only set min-height for the editor wrapper */
        .grapejs-editor-wrapper {
            min-height: 700px;
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased" x-data="siteLayout()">
    <?php if(auth()->check()): ?>
        <?php echo $__env->make('components.layout.grapejs-edit-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <?php echo $__env->make('components.layout.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main class="laralgrape-container min-h-screen flex flex-col">
        <!-- Page Content -->
        <div class="page-content flex-1">
            <?php echo $renderedHtml; ?>

        </div>
        
        <?php if(auth()->check()): ?>
            <!-- GrapesJS Editor Container (hidden by default) -->
            <div class="grapejs-editor-wrapper" style="display:none; min-height: 700px;">
                <div id="grapejs-frontend-editor" style="min-height: 700px;"></div>
            </div>
        <?php endif; ?>
    </main>

    <?php echo $__env->make('components.layout.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $appCss = Vite::asset('resources/css/app.css');
    ?>
    <script>
        window.grapesjsCanvasStyles = [<?php echo json_encode($appCss, 15, 512) ?>];
    </script>
    <?php if(auth()->check()): ?>
        <?php
            $blockService = app(\App\Services\BlockService::class);
            $grapesjsBlocks = $blockService->getGrapesJsBlocks();
        ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/grapesjs@0.22.8/dist/css/grapes.min.css">
        <script src="https://unpkg.com/grapesjs@0.22.8/dist/grapes.min.js"></script>
        <script type="module" src="<?php echo e(Vite::asset('resources/js/grapesjs-editor.js')); ?>"></script>
        <script>
            window.grapesjsBlocks = <?php echo json_encode($grapesjsBlocks, 15, 512) ?>;
            window.pageGrapesjsData = <?php echo json_encode($editingData ?? [], 15, 512) ?>;
            window.saveGrapesjsUrl = "<?php echo e(route('page.save-grapesjs', ['slug' => $page->slug])); ?>";
            function initializeFrontendEditor() {
                if (typeof grapesjs !== 'undefined' && typeof window.LaraGrapeGrapesJsEditor !== 'undefined') {
                    window.frontendGrapesJsEditor = new window.LaraGrapeGrapesJsEditor({
                        containerId: 'grapejs-frontend-editor',
                        mode: 'frontend',
                        saveUrl: window.saveGrapesjsUrl,
                        blocks: window.grapesjsBlocks,
                        initialData: window.pageGrapesjsData
                    });
                } else {
                    setTimeout(initializeFrontendEditor, 200);
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeFrontendEditor);
            } else {
                initializeFrontendEditor();
            }
        </script>
    <?php endif; ?>
</body>
</html> <?php /**PATH /Users/robinschoenmaker/StreatsDesign/LaraGrape/resources/views/components/layout/app.blade.php ENDPATH**/ ?>