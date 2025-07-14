{{-- @block id="image" label="Image" description="An image block with placeholder and alt text" icon="fas fa-image" --}}
@php $isEditorPreview = $isEditorPreview ?? false; @endphp
@if($isEditorPreview)
<div class="image-block text-center py-8 border-l-8 border-accent shadow-lg">
    <img src="https://via.placeholder.com/800x400" 
         alt="Placeholder image" 
         class="max-w-full h-auto rounded-lg shadow-lg mx-auto border-4 border-accent">
</div>
@else
<div class="image-block text-center py-8 border-l-8 border-accent shadow-lg">
    <img src="https://via.placeholder.com/800x400" 
         alt="Placeholder image" 
         class="max-w-full h-auto rounded-lg shadow-lg mx-auto border-4 border-accent"
         data-gjs-type="image"
         data-gjs-name="image">
</div> 
@endif 