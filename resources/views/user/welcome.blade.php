<x-layout>
    <x-slot name="title">
        <!-- Add your title here -->
    </x-slot>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center w-full h-[70vh] bg-no-repeat mt-5"
        style="background-image: url('{{ asset('images/slide1.svg') }}');">
        <div class="relative z-10 flex items-center h-full ml-36">
            <a href="{{ route('productSort')}}"
                class="px-10 py-5 bg-slate-950 text-3xl font-bold rounded-full shadow-lg hover:bg-slate-500 hover:scale-105 transition-all duration-300 text-white block text-center">
                SHOP NOW
            </a>
        </div>
    </section>

    <!-- Brand Section -->
    <!-- HTML CODE -->
    <div>
        <h2 class="font-semibold text-2xl pt-5">Trend Brand</h2>
        <p class="font-semibold text-lg text-right"><a href="{{ route('user.displaybrand') }}">More View</a></p>
    </div>

    <div class="relative mt-4 h-60 bg-white">
        <!-- Custom Navigation Buttons -->
        <button
            class="custom-swiper-button-prev absolute left-1 top-1/2 transform -translate-y-1/2 z-10 bg-white p-2 opacity-35 hover:opacity-80 rounded-full shadow-md hover:bg-gray-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-800" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button
            class="custom-swiper-button-next absolute right-1 top-1/2 transform -translate-y-1/2 z-10 bg-white p-2 opacity-35 hover:opacity-80 rounded-full shadow-md hover:bg-gray-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-800" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Swiper Container -->
        <div class="swiper product-slider p-50 bg-white">
            <div class="swiper-wrapper">
                @foreach ($brands as $brand)
                <a class="swiper-slide flex justify-center"
                    href="{{ route('productSort', ['type' => 'Brand :','brand'=>$brand['id'],'value'=>$brand['brand_name']]) }}">
                    <div
                        class="bg-white flex flex-col justify-center items-center py-3 shadow-lg hover:shadow-2xl transition duration-300">
                        <img src="{{ asset('/' . $brand['image']) }}" alt="{{ $brand['brand_name'] }}"
                            class="w-96 h-40 object-contain ">
                    </div>
                </a>
                @endforeach
            </div>
            <!-- Optional Pagination (if you want it) -->
            <!-- <div class="swiper-pagination mt-4"></div> -->
        </div>
    </div>
    <!-- Category Section -->

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-x-36 gap-y-10 mt-20">
        @foreach ($categories as $element)
        <x-card-category :image="$element['images']" :title="$element['category_name']" :category_id="$element['id']" />
        @endforeach
    </div>

    <!-- Discount Slider Section -->
    <div class="mt-8">
        <h2 class="font-semibold text-2xl pt-5">Discount</h2>
        <p class="font-semibold text-md text-right"><a href="">More View</a></p>
    </div>

    <div class="w-full max-w-full mx-auto pt-5 h-[550px]">
        <!-- Swiper Container Wrapper -->
        <div class="relative w-full py-8">
            <!-- Navigation Buttons -->
            <button
                class="custom-swiper-button-prev absolute left-3 top-1/2 -translate-y-1/2 z-20 bg-white opacity-35 hover:opacity-80 hover:bg-gray-100 text-gray-700 rounded-full shadow-lg p-3 transition-all duration-300 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button
                class="custom-swiper-button-next absolute right-3 top-1/2 -translate-y-1/2 z-20 bg-white opacity-35 hover:opacity-80 hover:bg-gray-100 text-gray-700 rounded-full shadow-lg p-3 transition-all duration-300 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Swiper Main Container -->
            <div class="swiper product-slider ">
                <div class="swiper-wrapper">
                    @foreach ($discounts as $discount)
                    @php
                    $element = $discount->toArray();
                    @endphp
                    @if (!empty($element['product_variant']))
                    @php
                    $product = $element['product_variant'][0];
                    @endphp
                    <div class="swiper-slide flex justify-center">
                        <div class="transition-transform transform hover:-translate-y-1  duration-300 ">
                            <x-card-product :productId="$element['id']" :name="$element['name'] ?? 'No Discount'"
                                :productImage="isset($product['product_images'][0]['images']) ? $product['product_images'][0]['images'] : 'default-image.jpg'"
                                :price="$product['price'] ?? 0" :discount="$product['discount']['discount'] ?? 0" />
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <!-- Trend Brand Section -->


    <!-- Product Section -->
    <div class="mt-10">
        <h2 class="font-semibold text-2xl pt-5">Product</h2>
        <p class="font-semibold text-md text-right"><a href="">More View</a></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-y-5 gap-x-6 mt-5">
        @foreach ($products as $product)

        @php
        $element=$product->toArray();
        $productVariant = $element['product_variant'][0] ?? null;
        @endphp

        @if ($productVariant)
        <x-card-product :productId="$element['id'] ?? '1'" :name="$element['name']"
            :price="$productVariant['price'] ?? 0"
            :productImage="$productVariant['product_images'][0]['images'] ?? 'default-image.jpg'"
            :discount="$productVariant['discount']['discount'] ?? null" />
        @endif
        @endforeach
    </div>
    <div class="mt-16 flex justify-center">
        {{ $products->links('pagination::tailwind') }}
    </div>


</x-layout>
@if (session('success'))
<script>
    // console.log("Order placed successfully!");
    localStorage.removeItem('cart');
</script>
@endif