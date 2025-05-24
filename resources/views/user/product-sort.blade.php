<x-layout>
    <div class="mt-16">
        <h2 class="font-semibold text-2xl py-5 ">{{ $title }}</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-y-5 gap-x-6 mt-5">
        @foreach ($products as $product)
        @php
        $productVariant = $product->productVariant[0] ?? null;
        @endphp

        @if ($productVariant)
        <x-card-product :productId="$product->id" :name="$product->name" :price="$productVariant->price ?? 0"
            :productImage="$productVariant->productImages[0]->images ?? 'default-image.jpg'"
            :discount="$productVariant->discount->discount ?? null" />
        @endif
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</x-layout>