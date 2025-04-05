@props(['name', 'productImage', 'price', 'discount' => null])

<a class="relative flex flex-col bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-200 ease-in-out transform hover:scale-105"
    href="/detail?name={{ urlencode($name) }}">

    <!-- Product Image -->
    <div class="relative w-full h-72">
        <img src="{{ $productImage }}" alt="Product Image" class="w-full h-full object-cover bg-top">

        <!-- Discount Badge -->
        @if (!empty($discount) && is_numeric($discount) && $discount > 0)
        <span class="absolute top-3 left-2 bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full">
            -{{ $discount }}% OFF
        </span>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-4 text-center">
        <p class="text-lg font-semibold text-gray-800">{{ $name }}</p>

        @php
        $discount = (float) $discount;
        $price = (float) $price;
        $discount_price = ($discount > 0) ? $price * ((100 - $discount) / 100) : $price;
        @endphp

        @if ($discount > 0)
        <p class="text-lg font-bold text-gray-900 mt-1">${{ number_format($discount_price, 2) }}</p>
        <p class="text-sm text-gray-500 line-through">${{ number_format($price, 2) }}</p>
        @else
        <p class="text-lg font-bold text-gray-900 mt-1">${{ number_format($price, 2) }}</p>
        @endif
    </div>
</a>