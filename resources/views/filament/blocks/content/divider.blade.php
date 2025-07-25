{{-- @block id="divider" label="Divider" description="A horizontal divider with customizable padding and styling" --}}
@php $isEditorPreview = $isEditorPreview ?? false; @endphp
@if($isEditorPreview)
<div class="divider-block">
    <hr class="border-t-2 border-primary-300 my-12">
    <!-- Accent option for variety -->
    <!-- <hr class="border-t-2 border-accent my-12"> -->
</div>
@else
<div class="divider-block" data-gjs-type="default" data-gjs-draggable="true" data-gjs-droppable="false">
    <hr class="border-t-2 border-primary-300 my-12">
    <!-- Accent option for variety -->
    <!-- <hr class="border-t-2 border-accent my-12"> -->
</div>
@endif 