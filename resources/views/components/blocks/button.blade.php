{{-- @block id="button" label="Button" description="A clickable button with customizable styling" --}}
<button {{ $attributes->merge(['class' => 'bg-gradient-to-r from-primary-600 via-accent to-secondary hover:from-primary-700 hover:to-accent text-primary-50 font-semibold py-3 px-8 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2']) }}>
    {{ $slot }}
</button> 