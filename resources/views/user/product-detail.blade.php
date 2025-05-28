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
                    <button id="add-to-cart-btn"
                        class="add-to-cart-btn w-full mt-4 px-6 py-3 bg-slate-950 text-white rounded-xl font-semibold hover:bg-slate-700 transition text-lg"
                        onclick="addToCartBtn(product)">
                        Add to Cart
                    </button>
                    <button id="add-to-favorite-btn"
                        class="add-to-favorite-btn w-full px-6 py-3 bg-white text-black rounded-xl font-semibold hover:bg-gray-200 border transition text-lg flex items-center justify-center gap-2">

                        {{-- Outline Heart (Not Favorited) --}}
                        <svg id="heart-outline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            strokeWidth={1.5} stroke="currentColor" class="w-6 h-6">
                            <path strokeLinecap="round" strokeLinejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>


                        {{-- Filled Heart (Favorited) --}}
                        <svg id="heart-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor" class="w-6 h-6 hidden">
                            <path
                                d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                        </svg>
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
            @foreach ($relatedProducts as $relatedProduct)

            @php
            $element = $relatedProduct->toArray();
            $productVariant = $element['product_variant'][0] ?? null;
            @endphp
            @if ($productVariant)
            <x-card-product :productId="$element['id']" :name="$element['name']" :price="$productVariant['price'] ?? 0"
                :productImage="$productVariant['product_images'][0]['images'] ?? 'default.jpg'"
                :discount="$productVariant['discount'] ?? null" />


            @endif
            @endforeach
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        const productVariants = @json($product['product_variant']);
        const productName = @json($product['name']);
        const colorBtns = document.querySelectorAll('.color-btn');
        const sizeContainer = document.getElementById('size-options');
        const mainImage = document.getElementById('main-image').querySelector('img');
        const gallery = document.getElementById('gallery');
        const price = document.getElementById('price');
        const stock = document.getElementById('stock');
        const favoritebtn = document.querySelector('.add-to-favorite-btn');
        let selectedColor;
        let filtered;
        let selectedVariant;
        let finalPrice;

        function updateDetail(variant) {
            mainImage.src = '/' + variant.product_images[0].images;
            const discount = variant.discount ? parseFloat(variant.discount.discount || 0) : 0;
            const originalPrice = parseFloat(variant.price || 0);
            finalPrice = discount > 0 ? originalPrice * (1 - discount / 100) : originalPrice;
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
            filtered = productVariants.filter(v => v.color === color);
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
                    selectedVariant = variant;
                });

                sizeContainer.appendChild(btn);
            });

            setTimeout(() => {
                const firstBtn = sizeContainer.querySelector('button');
                if (firstBtn) firstBtn.click();
            }, 0);
        }

        // Thumbnail click to update main image
        gallery.addEventListener('click', (e) => {
            if (e.target.tagName === 'IMG' && e.target.dataset.img) {
                mainImage.src = e.target.dataset.img;
            }
        });

        // Color selection
        colorBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                selectedColor = btn.dataset.color;
                renderSizeButtons(selectedColor);

                colorBtns.forEach(b => b.classList.remove('ring-2', 'ring-black'));
                btn.classList.add('ring-2', 'ring-black');
            });
        });

        // Initialize
        renderSizeButtons(productVariants[0]?.color);
        if (colorBtns[0]) colorBtns[0].classList.add('ring-2', 'ring-black');

        // Add to Cart
        document.getElementById('add-to-cart-btn').addEventListener('click', function(e) {
            e.preventDefault();


            if (!selectedVariant) {
                alert('Please select a size first!');
                return;
            }

            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            const existingProduct = cart.findIndex(item => item.productVariantsId === selectedVariant.id);

            if (existingProduct !== -1) {
                cart[existingProduct].quantity += 1;
            } else {
                console.log('n', selectedVariant);

                cart.push({
                    id: selectedVariant.product_id,
                    productVariantsId: selectedVariant.id,
                    productName: productName,
                    originalPrice: selectedVariant.price,
                    discount: selectedVariant.discount?.discount || 0,
                    image: selectedVariant.product_images[0].images,
                    size: selectedVariant.size,
                    quantity: 1
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));

        });
        favoritebtn.addEventListener("click", function(e) {
            e.preventDefault();

            if (!selectedVariant) {
                alert('Please select a size first!');
                return;
            }

            let favorite = JSON.parse(localStorage.getItem('favorite')) || [];

            const exists = favorite.find(item => item.productVariantsId === selectedVariant.id);

            if (exists) {
                favorite = favorite.filter(item => item.productVariantsId !== selectedVariant.id);
                localStorage.setItem('favorite', JSON.stringify(favorite));
            } else {
                favorite.push({
                    id: selectedVariant.product_id,
                    productVariantsId: selectedVariant.id,
                    productName: productName,
                    originalPrice: selectedVariant.price,
                    discount: selectedVariant.discount?.discount || 0,
                    image: selectedVariant.product_images[0].images,
                    size: selectedVariant.size,
                });
                localStorage.setItem('favorite', JSON.stringify(favorite));

            }

            updateHeartIcon(selectedVariant.product_id); // <-- Update icon after change
        });

        const heartOutline = document.getElementById('heart-outline');
        const heartFilled = document.getElementById('heart-filled');

        function updateHeartIcon(product_id) {
            const favorite = JSON.parse(localStorage.getItem('favorite')) || [];
            const isFavorited = favorite.some(item => item.id === product_id);

            if (isFavorited) {
                heartOutline.classList.add('hidden');
                heartFilled.classList.remove('hidden');
            } else {
                heartOutline.classList.remove('hidden');
                heartFilled.classList.add('hidden');
            }
        }
    </script>


</x-layout>