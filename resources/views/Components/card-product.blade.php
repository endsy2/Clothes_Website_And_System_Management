@props(['name', 'productImage', 'price', 'discount' => null])

<a class="relative flex flex-col w-full sm:w-56 md:w-60 lg:w-72 xl:w-80 bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 ease-in-out transform hover:scale-105"
    href="/detail?name={{ $name }}">

    <!-- Product Image -->
    <div class="relative w-full h-72">
        <img src="{{ $productImage }}" alt="Product Image" class="w-full h-full object-cover bg-top">

        <!-- Discount Badge -->
        @if ($discount)
        <span class="absolute top-3 left-2 bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full">
            -{{ $discount }}% OFF
        </span>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-4 flex flex-col items-center text-center">
        <p class="text-lg font-semibold text-gray-800">{{ $name }}</p>
        <p class="text-lg font-bold text-gray-900 mt-1">${{ number_format($price, 2) }}</p>
    </div>
</a>