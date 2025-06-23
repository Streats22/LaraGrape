{{-- @block id="card" label="Card" description="A card component with image, title, and description" --}}
<div class="max-w-sm mx-auto bg-white rounded-xl shadow-md overflow-hidden">
    <img class="w-full h-48 object-cover" 
         src="https://via.placeholder.com/400x200" 
         alt="Card image"
         data-gjs-type="image"
         data-gjs-name="card-image">
    <div class="p-6">
        <h3 class="text-xl font-bold mb-2" data-gjs-type="text" data-gjs-name="card-title">Card Title</h3>
        <p class="text-gray-600 mb-4" data-gjs-type="text" data-gjs-name="card-description">Card description goes here</p>
        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" data-gjs-type="text" data-gjs-name="card-button">Learn More</button>
    </div>
</div> 