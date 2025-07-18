{{-- @block id="gallery" label="Image Gallery" description="A responsive image gallery with multiple images" --}}
@php $isEditorPreview = $isEditorPreview ?? false; @endphp
@if($isEditorPreview)
<div class="gallery-block py-8 border-l-8 border-accent shadow-lg bg-primary-50 dark:bg-primary-900">
    <div class="container mx-auto px-4">
        <h3 class="text-2xl font-extrabold mb-6 text-center text-primary-900 dark:text-primary-50">Image Gallery</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="gallery-item">
                <img 
                    src="https://via.placeholder.com/400x300/3B82F6/FFFFFF?text=Image+1" 
                    alt="Gallery Image 1"
                    class="w-full h-64 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-2 border-accent">
            </div>
            <div class="gallery-item">
                <img 
                    src="https://via.placeholder.com/400x300/10B981/FFFFFF?text=Image+2" 
                    alt="Gallery Image 2"
                    class="w-full h-64 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-2 border-accent">
            </div>
            <div class="gallery-item">
                <img 
                    src="https://via.placeholder.com/400x300/F59E0B/FFFFFF?text=Image+3" 
                    alt="Gallery Image 3"
                    class="w-full h-64 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-2 border-accent">
            </div>
        </div>
        <p class="text-primary-700 dark:text-primary-200 mt-6 text-center">
            Add a description for your gallery here. Explain what these images represent or showcase.
        </p>
    </div>
</div>
@else
<div class="gallery-block py-8 border-l-8 border-accent shadow-lg bg-primary-50 dark:bg-primary-900">
    <div class="container mx-auto px-4">
        <h3 class="text-2xl font-extrabold mb-6 text-center text-primary-900 dark:text-primary-50" data-gjs-type="text" data-gjs-name="gallery-title">Image Gallery</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="gallery-item">
                <img 
                    src="https://via.placeholder.com/400x300/3B82F6/FFFFFF?text=Image+1" 
                    alt="Gallery Image 1"
                    class="w-full h-64 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-2 border-accent"
                    data-gjs-type="image"
                    data-gjs-name="gallery-image-1">
            </div>
            <div class="gallery-item">
                <img 
                    src="https://via.placeholder.com/400x300/10B981/FFFFFF?text=Image+2" 
                    alt="Gallery Image 2"
                    class="w-full h-64 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-2 border-accent"
                    data-gjs-type="image"
                    data-gjs-name="gallery-image-2">
            </div>
            <div class="gallery-item">
                <img 
                    src="https://via.placeholder.com/400x300/F59E0B/FFFFFF?text=Image+3" 
                    alt="Gallery Image 3"
                    class="w-full h-64 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-2 border-accent"
                    data-gjs-type="image"
                    data-gjs-name="gallery-image-3">
            </div>
        </div>
        <p class="text-primary-700 dark:text-primary-200 mt-6 text-center" data-gjs-type="text" data-gjs-name="gallery-description">
            Add a description for your gallery here. Explain what these images represent or showcase.
        </p>
    </div>
</div>
@endif 