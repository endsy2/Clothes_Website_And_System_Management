<x-layout>
    <div class="max-w-7xl mx-auto p-6 mt-10">
        <div class="flex flex-col md:flex-row gap-10">
            {{-- Images Section --}}
            <div class="flex flex-col-reverse md:flex-row gap-6 w-full md:w-1/2">
                {{-- Gallery Thumbnails --}}
                <div id="gallery"
                    class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto md:h-[600px] scrollbar-thin scrollbar-thumb-gray-300 p-1">
                    @foreach ($product['product_variant'][0]['product_images'] ?? [] as $img)
                    <img src="{{ asset($img['images']) }}"
                        class="w-20 h-32 object-cover border border-gray-300 cursor-pointer hover:ring-2 ring-[#128B9E] transition"
                        data-img="{{ asset($img['images']) }}">
                    @endforeach
                </div>

                {{-- Main Image --}}
                <div id="main-image" class="flex-1">
                    <img src="{{ asset($product['product_variant'][0]['product_images'][0]['images'] ?? 'default.jpg') }}"
                        alt="product image" class="w-full aspect-[6/8] object-cover transition-all duration-300">
                </div>
            </div>

            {{-- Product Info --}}
            <div class="w-full md:w-1/2 space-y-8">
                {{-- Product Title & Brand --}}
                <div class="mb-6 border-b pb-4">
                    <h1 class="text-4xl font-bold text-gray-900">{{ $product['name'] }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Brand: {{ $product['brand']['brand_name'] }}</p>
                    @php
                    $firstVariant = $product['product_variant'][0] ?? null;
                    $price = floatval($firstVariant['price'] ?? 0);
                    $discount = floatval($firstVariant['discount'] ?? 0);
                    $finalPrice = $discount > 0 ? $price * (1 - $discount / 100) : $price;
                    @endphp


                    {{-- Price --}}
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-semibold text-gray-900">Price:</span>
                        <div id="price" class="flex flex-col sm:flex-row sm:items-center gap-2">
                            @if ($discount > 0)
                            <span class="text-gray-500 line-through text-lg">
                                ${{ number_format($price, 2) }}
                            </span>
                            <span
                                class="bg-green-100 text-green-800 font-bold px-4 py-1 rounded-full shadow-sm text-lg">
                                ${{ number_format($finalPrice, 2) }}
                            </span>
                            @else
                            <span
                                class="bg-green-100 text-green-800 font-bold px-4 py-1 rounded-full shadow-sm text-lg">
                                ${{ number_format($price, 2) }}
                            </span>
                            @endif
                        </div>
                        <span class="ml-auto text-sm text-gray-600">Stock:
                            <span id="stock" class="font-medium">
                                {{ $firstVariant['stock'] ?? 'N/A' }}
                            </span>
                        </span>
                    </div>


                </div>

                {{-- Color Options --}}
                <div>
                    <h2 class="text-lg font-semibold mb-2">Choose Color:</h2>
                    <div class="flex gap-3">
                        @php
                        $colors = collect($product['product_variant'])->unique('color');
                        @endphp
                        @foreach ($colors as $colorVariant)
                        <button
                            class="color-btn w-16 h-16  overflow-hidden border border-gray-300 hover:border-black transition"
                            data-color="{{ $colorVariant['color'] }}">
                            <img src="{{ asset($colorVariant['product_images'][0]['images'] ?? 'default.jpg') }}"
                                class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Size Options --}}
                <div>
                    <h2 class="text-lg font-semibold mb-2">Choose Size:</h2>
                    <div id="size-options" class="flex flex-wrap gap-3">
                        {{-- Size buttons will be rendered by JS --}}
                    </div>
                </div>

                {{-- Description --}}
                <div class="text-lg">
                    <p><strong>Description:</strong> <span id="description">{{ $product['description'] }}</span></p>
                </div>

                {{-- Add to Cart --}}
                <div class="flex flex-col gap-4">
                    <button
                        class="w-full mt-4 px-6 py-3 bg-slate-950 text-white rounded-xl font-semibold hover:bg-slate-700 transition text-lg">
                        Add to Cart
                    </button>
                    <button
                        class="w-full px-6 py-3 bg-white text-black rounded-xl font-semibold hover:bg-gray-200 border transition text-lg">
                        Add to Favorite
                    </button>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        <div class="mt-10">
            <h2 class="font-semibold text-2xl pt-5">Similar Product</h2>
            <p class="font-semibold text-md text-right"><a href="">More View</a></p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-y-5 gap-x-6 mt-5">
            @foreach ($relatedProducts as $relatedProduc)
            @php $productVariant = $relatedProduc['product_variant'][0] ?? null; @endphp
            @if ($productVariant)
            <x-card-product :productId="$relatedProduc['id']" :name="$relatedProduc['name']"
                :price="$productVariant['price'] ?? 0"
                :product_image="$productVariant['product_images'][0]['images'] ?? 'default.jpg'"
                :discount="$productVariant['discount'] ?? null" />

            @endif
            @endforeach
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        const productVariants = @json($product['product_variant']);
        const colorBtns = document.querySelectorAll('.color-btn');
        const sizeContainer = document.getElementById('size-options');
        const mainImage = document.getElementById('main-image').querySelector('img');
        const gallery = document.getElementById('gallery');
        const price = document.getElementById('price');
        const stock = document.getElementById('stock');

        function updateDetail(variant) {
            mainImage.src = '/' + variant.product_images[0].images;
            const discount = parseFloat(variant.discount.discount || 0);
            const originalPrice = parseFloat(variant.price || 0);
            const finalPrice = discount > 0 ? originalPrice * (1 - discount / 100) : originalPrice;

            if (discount > 0) {
                price.innerHTML = `
            <span class="text-gray-500 line-through text-base mr-2">$${originalPrice.toFixed(2)}</span>
            <span class="bg-green-100 text-green-800 font-bold px-3 py-1 rounded-full shadow-sm text-base">
                $${finalPrice.toFixed(2)}
            </span>
        `;
            } else {
                price.innerHTML = `
            <span class="bg-green-100 text-green-800 font-bold px-3 py-1 rounded-full shadow-sm text-base">
                $${originalPrice.toFixed(2)}
            </span>
        `;
            }

            stock.textContent = variant.stock;

            // Update gallery
            gallery.innerHTML = '';
            variant.product_images.forEach(img => {
                const thumb = document.createElement('img');
                thumb.src = '/' + img.images;
                thumb.className =
                    'w-20 h-32 object-cover border border-gray-300 cursor-pointer hover:ring-2 ring-black transition';
                thumb.dataset.img = '/' + img.images;
                gallery.appendChild(thumb);
            });
        }


        function renderSizeButtons(color) {
            const filtered = productVariants.filter(v => v.color === color);
            sizeContainer.innerHTML = '';

            filtered.forEach(variant => {
                const btn = document.createElement('button');
                btn.className =
                    'size-btn px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-100 transition';
                btn.textContent = variant.size;
                btn.dataset.variant = JSON.stringify(variant);

                btn.addEventListener('click', () => {
                    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('bg-black',
                        'text-white'));
                    btn.classList.add('bg-black', 'text-white');
                    updateDetail(variant);
                });

                sizeContainer.appendChild(btn);
            });

            if (filtered[0]) {
                updateDetail(filtered[0]);
                sizeContainer.querySelector('button').click(); // Select first by default
            }
        }

        // Thumbnail click to update main image
        gallery.addEventListener('click', (e) => {
            if (e.target.tagName === 'IMG' && e.target.dataset.img) {
                mainImage.src = e.target.dataset.img;
            }
        });


        // Color selection with proper ring effect (black ring)
        colorBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const selectedColor = btn.dataset.color;
                renderSizeButtons(selectedColor);

                // Remove ring from all color buttons
                colorBtns.forEach(b => b.classList.remove('ring-2', 'ring-black'));

                // Add black ring to active button
                btn.classList.add('ring-2', 'ring-black');
            });
        });


        // Initialize
        renderSizeButtons(productVariants[0]?.color);
        if (colorBtns[0]) colorBtns[0].classList.add('ring-2', 'ring-black');
    </script>
</x-layout>