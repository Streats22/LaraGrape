{{-- @block id="hero" label="Hero Section" description="A hero section with title, subtitle, and CTA" icon="fas fa-star" --}}
@php $isEditorPreview = $isEditorPreview ?? false; @endphp
@if($isEditorPreview)
<div class="hero-block bg-primary-50 dark:bg-primary-900 text-primary-900 dark:text-primary-100 py-20 px-4 border-b-8 border-accent shadow-2xl">
    <div class="max-w-4xl mx-auto text-center">
        <div class="mb-8">
            <h1 class="text-5xl font-extrabold mb-6 text-primary-900 dark:text-primary-100 drop-shadow-lg">Welcome to Our Site</h1>
            <p class="text-xl text-primary-700 dark:text-primary-200 mb-8 max-w-2xl mx-auto">This is a beautiful hero section. You can edit the title, subtitle, and add content below.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-accent text-primary-900 dark:text-primary-900 px-8 py-3 rounded-lg font-semibold hover:bg-primary-100 hover:text-accent transition-colors shadow" disabled>Get Started</button>
                <button class="border-2 border-accent text-accent px-8 py-3 rounded-lg font-semibold hover:bg-primary-100 hover:text-accent transition-colors shadow" disabled>Learn More</button>
            </div>
        </div>
        <div class="min-h-32 bg-primary-50 dark:bg-primary-900 p-6 rounded-lg border-2 border-dashed border-accent border-opacity-30 flex items-center justify-center shadow-md">
            <div class="text-center">
                <svg class="w-10 h-10 text-accent opacity-70 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <p class="text-primary-700 dark:text-primary-200 opacity-80 font-medium">Add content here</p>
                <p class="text-sm text-primary-700 dark:text-primary-200 opacity-60 mt-1">Drop blocks, text, or images</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="hero-block bg-primary-50 dark:bg-primary-900 text-primary-900 dark:text-primary-100 py-20 px-4 border-b-8 border-accent shadow-2xl" data-gjs-type="default" data-gjs-draggable="true" data-gjs-droppable="true" data-gjs-name="hero">
    <div class="max-w-4xl mx-auto text-center">
        <div class="mb-8">
            <h1 class="text-5xl font-extrabold mb-6 text-primary-900 dark:text-primary-100 drop-shadow-lg" data-gjs-type="text" data-gjs-name="hero-title">Welcome to Our Site</h1>
            <p class="text-xl text-primary-700 dark:text-primary-200 mb-8 max-w-2xl mx-auto" data-gjs-type="text" data-gjs-name="hero-subtitle">This is a beautiful hero section. You can edit the title, subtitle, and add content below.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-accent text-primary-900 dark:text-primary-900 px-8 py-3 rounded-lg font-semibold hover:bg-primary-100 hover:text-accent transition-colors shadow" data-gjs-type="text" data-gjs-name="hero-cta-primary">Get Started</button>
                <button class="border-2 border-accent text-accent px-8 py-3 rounded-lg font-semibold hover:bg-primary-100 hover:text-accent transition-colors shadow" data-gjs-type="text" data-gjs-name="hero-cta-secondary">Learn More</button>
            </div>
        </div>
        <div class="min-h-32 bg-primary-50 dark:bg-primary-900 p-6 rounded-lg border-2 border-dashed border-accent border-opacity-30 flex items-center justify-center shadow-md" data-gjs-droppable="true" data-gjs-name="hero-content">
            <div class="text-center">
                <svg class="w-10 h-10 text-accent opacity-70 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <p class="text-primary-700 dark:text-primary-200 opacity-80 font-medium">Add content here</p>
                <p class="text-sm text-primary-700 dark:text-primary-200 opacity-60 mt-1">Drop blocks, text, or images</p>
            </div>
        </div>
    </div>
</div>
@endif 