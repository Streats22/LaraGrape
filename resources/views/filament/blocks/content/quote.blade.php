{{-- @block id="quote" label="Quote Block" description="A styled quote block for testimonials or quotes" --}}
@php $isEditorPreview = $isEditorPreview ?? false; @endphp
@if($isEditorPreview)
<div class="quote-block bg-primary-50 dark:bg-primary-900 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="quote-icon mb-6">
                <svg class="w-12 h-12 text-primary-500 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
            </div>
            <blockquote class="text-2xl font-medium text-primary-800 dark:text-primary-100 mb-6">
                "This is an amazing quote that will inspire your visitors. Make it meaningful and impactful."
            </blockquote>
            <div class="quote-author">
                <p class="text-lg font-semibold text-primary-700 dark:text-primary-200">Author Name</p>
                <p class="text-primary-600 dark:text-primary-200">Author Title</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="quote-block bg-primary-50 dark:bg-primary-900 py-12" data-gjs-type="default" data-gjs-draggable="true" data-gjs-droppable="false">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="quote-icon mb-6">
                <svg class="w-12 h-12 text-primary-500 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
            </div>
            <blockquote class="text-2xl font-medium text-primary-800 dark:text-primary-100 mb-6" data-gjs-type="text" data-gjs-name="quote-text">
                "This is an amazing quote that will inspire your visitors. Make it meaningful and impactful."
            </blockquote>
            <div class="quote-author">
                <p class="text-lg font-semibold text-primary-700 dark:text-primary-200" data-gjs-type="text" data-gjs-name="quote-author">Author Name</p>
                <p class="text-primary-600 dark:text-primary-200" data-gjs-type="text" data-gjs-name="quote-title">Author Title</p>
            </div>
        </div>
    </div>
</div>
@endif 