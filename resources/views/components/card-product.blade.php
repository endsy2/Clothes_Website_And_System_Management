@props(['productId','name', 'productImage', 'price', 'discount' => null])


<a href="/detail?id={{ urlencode($productId) }}"
    class="group relative flex flex-col bg-white  shadow-md  transition-all duration-300 overflow-hidden hover:scale-[1.03]">

    {{-- Product Image --}}
    <div class="relative w-full aspect-[4/5] bg-gray-100 overflow-hidden">
        <img src="{{ asset($productImage) }}" alt="{{ $name }}"
            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />

        {{-- Discount Badge --}}
        @if (!empty($discount) && is_numeric($discount) && $discount > 0)
        <div
            class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
            -{{ $discount }}% OFF
        </div>
        @endif
    </div>

    {{-- Product Details --}}
    <div class="p-4 text-center">
        <p class="text-base font-medium text-gray-800 truncate">{{ $name }}</p>

        @php
        $discount = (float) $discount;
        $price = (float) $price;
        $discount_price = ($discount > 0) ? $price * ((100 - $discount) / 100) : $price;
        @endphp

        <div class="mt-1">
            @if ($discount > 0)
            <p class="text-lg font-semibold text-[#128B9E]">${{ number_format($discount_price, 2) }}</p>
            <p class="text-sm text-gray-400 line-through">${{ number_format($price, 2) }}</p>
            @else
            <p class="text-lg font-semibold text-gray-900">${{ number_format($price, 2) }}</p>
            @endif
        </div>
    </div>
</a>