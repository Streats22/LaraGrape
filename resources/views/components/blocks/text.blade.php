{{-- @block id="text" label="Text Block" description="A text block with customizable styling" --}}
<div {{ $attributes->merge(['class' => 'max-w-3xl mx-auto py-8 px-4 bg-primary-50 rounded-xl shadow-md']) }}>
    <p class="text-primary-800 text-lg leading-relaxed font-medium">
        {{ $slot }}
    </p>
</div> 