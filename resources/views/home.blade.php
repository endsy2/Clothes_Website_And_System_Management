<x-layout>
    <x-slot name="title">
        Home page
    </x-slot>

    @foreach($products as $product)
    <div>
        <h2>product:{{ $product->name }}</h2>
        <p>descripton:{{ $product->description }}</p>

        <!-- Access the related Category and Brand -->
        <p>Category: {{ $product->category->category_name ?? 'No category' }}</p>
        <p>Brand: {{ $product->brand->brand_name ?? 'No brand' }}</p>
    </div>
    @endforeach

    <!-- Dump the products data to inspect -->
    @dd($products)
</x-layout>