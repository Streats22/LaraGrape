<?php
    $id = $getId();
    $isDisabled = $isDisabled();
    $statePath = $getStatePath();
    $height = $getHeight();
    $state = $getState();
    
    // Load blocks dynamically from BlockService
    $blockService = app(\App\Services\BlockService::class);
    $blocks = $blockService->getGrapesJsBlocks();

    $appCss = Vite::asset('resources/css/app.css');
    $siteCss = Vite::asset('resources/css/site.css');
    $grapesCss = Vite::asset('resources/css/filament-grapesjs-editor.css');
    $adminCss = Vite::asset('resources/css/filament/admin/theme.css');
    
    // Try to get the record (could be a model or a slug)
    $record = null;
    $pageId = null;
    $saveUrl = null;
    $isCreate = true;

    try {
        $record = $getRecord();
    } catch (\Throwable $e) {
        $record = null;
    }

    // If $record is a model, get the ID
    if (is_object($record) && method_exists($record, 'getKey')) {
        $pageId = $record->getKey();
        $isCreate = false;
    }
    // If $record is a string (slug), look up the page
    elseif (is_string($record)) {
        $page = \App\Models\Page::where('slug', $record)->first();
        if ($page) {
            $pageId = $page->id;
            $isCreate = false;
        }
    }

    if ($pageId) {
        $saveUrl = route('admin.page.save-grapesjs', $pageId);
    }
    
    // Debug information
    \Log::info('GrapesJS Editor Debug', [
        'record_exists' => $record ? 'yes' : 'no',
        'record_id' => $pageId,
        'isCreate' => $isCreate,
        'saveUrl' => $saveUrl,
        'statePath' => $statePath,
        'url' => request()->url(),
        'route_name' => request()->route()->getName() ?? 'unknown',
        'route_parameters' => request()->route()->parameters() ?? []
    ]);
?>

<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $getFieldWrapperView()] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => $field]); ?>
    <div class="grapesjs-editor-wrapper" id="wrapper-<?php echo e($id); ?>">
        <div class="grapesjs-controls">
            <button 
                type="button" 
                class="fullscreen-toggle-btn"
                title="Toggle Fullscreen Mode (Press Escape to exit)"
            >
                <svg class="fullscreen-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                </svg>
                <svg class="exit-fullscreen-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                    <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
                </svg>
            </button>
            <button type="button" class="grapesjs-save-btn" style="margin: 10px 0; padding: 8px 18px; background: #9333ea; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Save</button>
        </div>
        <div 
            id="<?php echo e($id); ?>"
            class="grapesjs-editor"
            style="height: <?php echo e($height); ?>; min-height: 400px; border: 1.5px solid #e5e7eb; background: #fff;"
            data-state-path="<?php echo e($statePath); ?>"
            data-current-state="<?php echo e(json_encode($state)); ?>"
            data-height="<?php echo e($height); ?>"
            data-disabled="<?php echo e($isDisabled ? 'true' : 'false'); ?>"
            data-blocks="<?php echo e(json_encode($blocks)); ?>"
            data-page-id="<?php echo e($pageId); ?>"
            data-save-url="<?php echo e($saveUrl); ?>"
            data-is-create="<?php echo e($isCreate ? 'true' : 'false'); ?>"
        >
        </div>
        <?php echo e($getChildComponentContainer()); ?>

    </div>
    
    <?php $__env->startPush('scripts'); ?>
        <script type="module" src="<?php echo e(Vite::asset('resources/js/grapesjs-editor.js')); ?>"></script>
        <script>
            window.grapesjsCanvasStyles = [<?php echo json_encode($appCss, 15, 512) ?>];
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new window.LaraGrapeGrapesJsEditor({
                    containerId: '<?php echo e($id); ?>',
                    mode: 'backend',
                    statePath: '<?php echo e($statePath); ?>',
                    blocks: <?php echo json_encode($blocks, 15, 512) ?>,
                    initialData: <?php echo json_encode($state, 15, 512) ?>,
                    isDisabled: <?php echo e($isDisabled ? 'true' : 'false'); ?>,
                    height: '<?php echo e($height); ?>'
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
    
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
            box-sizing: border-box;
            isolation: isolate;
        }
        
        .grapesjs-controls {
            position: absolute;
            top: 80px;
            left: 15px;
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
                top: 60px;
                left: 10px;
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php /**PATH /Users/robinschoenmaker/StreatsDesign/boilerplate/resources/views/filament/forms/components/grapesjs-editor.blade.php ENDPATH**/ ?>