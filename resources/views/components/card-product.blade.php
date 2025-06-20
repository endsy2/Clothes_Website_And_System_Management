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
        {{-- Spinner --}}
        <div id="loader-{{ $productId }}" class="absolute inset-0 flex items-center justify-center bg-gray-100 z-10">
            <svg class="w-6 h-6 text-black animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
            </svg>
        </div>

        {{-- Product Image --}}
        <img src="{{ asset('/' . $productImage) }}" alt="{{ $name }}"
            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110 opacity-0"
            id="image-{{ $productId }}" onload="imageLoaded({{ $productId }})">

        {{-- Discount Badge --}}
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
<script>
    function imageLoaded(productId) {
        const loader = document.getElementById('loader-' + productId);
        const image = document.getElementById('image-' + productId);
        if (loader && image) {
            loader.style.display = 'none';
            image.style.opacity = '1';
        }
    }
</script>