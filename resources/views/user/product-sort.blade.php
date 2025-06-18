<x-layout>
    <div class="mt-16">
        <h2 class="font-semibold text-2xl py-5">
            {{ $title ?? 'Products' }} {{ $value ?? '' }}
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-y-5 gap-x-6 mt-5">
        @if (!empty($products['data']) && is_array($products['data']))
        @foreach ($products['data'] as $product)
        @php
        $productVariant = $product['product_variant'][0] ?? null;
        $productId = $product['id'] ?? null;
        $productName = $product['name'] ?? 'Unnamed Product';
        $price = $productVariant['price'] ?? 0;
        $image = $productVariant['product_images'][0]['images'] ?? 'default-image.jpg';
        $discount = $productVariant['discount']['discount'] ?? null;
        @endphp

        @if ($productVariant && $productId)
        <x-card-product :productId="$productId" :name="$productName" :price="$price" :productImage="$image"
            :discount="$discount" />
        @endif
        @endforeach
        @else
        <p class="text-gray-500 col-span-full text-center">No products found.</p>
        @endif
    </div>
</x-layout>