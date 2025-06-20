@props(['productId', 'name', 'productImage', 'price', 'discount' => null])

@php
$discount = (float) $discount;
$price = (float) $price;
$discount_price = ($discount > 0) ? $price * ((100 - $discount) / 100) : $price;
@endphp

<a href="/detail?id={{ urlencode($productId) }}"
    class="group flex flex-col bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.03] h-[360px]">

    {{-- Image --}}
    <div class="relative h-2/3 bg-gray-100 overflow-hidden">
        <img src="{{ asset('/' . $productImage) }}" alt="{{ $name }}"
            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />

        @if ($discount > 0)
        <div
            class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md ring-1 ring-white/20 backdrop-blur-sm">
            -{{ $discount }}% OFF
        </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="flex flex-col justify-between flex-grow p-4 text-center">
        <h3 class="text-sm font-semibold text-gray-900 truncate mb-1">{{ $name }}</h3>

        @if ($discount > 0)
        <div>
            <div class="text-[#128B9E] text-base font-bold">${{ number_format($discount_price, 2) }}</div>
            <div class="text-gray-400 text-sm line-through">${{ number_format($price, 2) }}</div>
        </div>
        @else
        <div class="text-gray-900 text-base font-bold">${{ number_format($price, 2) }}</div>
        @endif
    </div>
</a>