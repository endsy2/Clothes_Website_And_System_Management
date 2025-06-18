@php
$sizebtns=['S','M','L','XL','2XL'];
@endphp
<x-admin-layout>
    <div class="max-w-7xl mx-auto p-6 mt-10">
        <div class="flex flex-col md:flex-row gap-10">
            {{-- Images Section --}}
            <div class="flex flex-col-reverse md:flex-row gap-6 w-full md:w-1/2">
                {{-- Gallery Thumbnails --}}
                <div id="gallery"
                    class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto md:h-[600px] scrollbar-thin scrollbar-thumb-gray-300 p-1">
                    @foreach ($product['product_variant'][0]['product_images'] ?? [] as $img)
                    @php
                    $file= $img['images'];
                    @endphp
                    <img src="{{ asset($img['images']) }}"
                        class="w-20 h-32 object-cover border border-gray-300 cursor-pointer hover:ring-2 ring-[#128B9E] transition"
                        data-img="{{ asset($img['images']) }}">

                    @endforeach
                </div>

                {{-- Main Image --}}
                <div id="main-image" class="flex-1">
                    @php
                    $imagePath = ltrim($product['product_variant'][0]['product_images'][0]['images'], '/');
                    $fullImageUrl = asset( $imagePath);

                    @endphp

                    <img src="{{ $fullImageUrl }}" alt="product image" id="main-product-image"
                        class="w-full aspect-[6/8] object-cover transition-all duration-300">
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
                <div class="flex gap-7 ">
                    <button id="edit-product-btn" class="bg-black text-white px-6 py-3 rounded-md hover:bg-gray-500">
                        Edit Product
                    </button>


                    <button id="edit-product-variant-btn"
                        class="bg-black text-white px-6 py-3 rounded-md hover:bg-gray-500">Edit Product
                        Variant</button>
                    <form action={{ route('admin.delete-product-variant',)  }}></form>
                    <button id="delete-product-variant-btn"
                        class="bg-black text-white px-6 py-3 rounded-md hover:bg-gray-500">Delete Product
                        Variant</button>
                </div>



            </div>
        </div>

        {{-- Related Products --}}
        <!-- Overlay with backdrop blur effect -->


        <div id="edit-product-form-overlay"
            class="hidden fixed inset-0 z-50 flex items-center justify-center  bg-opacity-100 backdrop-blur-sm">
            <div id="edit-product-form"
                class="bg-white rounded-2xl p-8 w-full max-w-xl relative shadow-2xl transform transition-all duration-300">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Product</h2>

                <form action="{{ route('admin.productupdate', $product['id']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="product-name" class="block text-lg font-medium text-gray-700">Product
                            Name</label>
                        <input type="text" name="name" value="{{ $product['name'] }}" id="product-name"
                            class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition">
                    </div>
                    <!-- Brand Dropdown -->
                    <div class="mb-6">
                        <label for="brand_name" class="block text-sm font-semibold text-gray-700 mb-2">Brands</label>
                        <select name="brand_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-black focus:outline-none">
                            <option value="">{{ $product['brand']['brand_name']}}</option>
                            @foreach ($brands as $brand)
                            <option value="{{ $brand['brand_name'] }}">{{ $brand['brand_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Category Dropdown -->
                    <div class="mb-6">
                        <label for="category_name"
                            class="block text-sm font-semibold text-gray-700 mb-2">Categorys</label>
                        <select name="category_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-black focus:outline-none">
                            <option value="">{{ $product['category']['category_name']}}</option>
                            @foreach ($categorys as $category)
                            <option value="{{ $category['category_name'] }}">{{ $category['category_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Product type Dropdown -->
                    <div class="mb-6">
                        <label for="product_type_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Product Types
                        </label>
                        <select name="product_type_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-black focus:outline-none">
                            <option value="{{ $product['product_type']['product_type_name'] ?? '' }}">
                                {{ $product['product_type']['product_type_name'] ?? 'Select product type' }}
                            </option>
                            @foreach ($productTypes as $productType)
                            <option value="{{ $productType['product_type_name'] }}">
                                {{ $productType['product_type_name'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="product-description"
                            class="block text-sm font-semibold text-gray-700 mb-2">Description:</label>
                        <textarea name="description" id="product-description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-black focus:outline-none resize-none">{{ $product['description'] }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancel-edit-product-btn"
                            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-black text-white rounded-md hover:bg-gray-800 transition">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
        {{-- edit product variant --}}
        <!-- Overlay with backdrop blur effect -->
        <div id="edit-product-variant-form-overlay"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-opacity-50 backdrop-blur-sm">
            <div id="edit-product-variant-form"
                class="bg-white rounded-2xl p-8 w-full max-w-xl relative shadow-2xl transform transition-all duration-300">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Product</h2>

                <form id="variant-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Size Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Size</label>
                        <div id="size-options" class="flex flex-wrap gap-2">
                            @foreach ($sizebtns as $sizebtn)
                            <button type="button"
                                class="size-btn px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-200 transition"
                                onclick="selectSizeBtn(this, '{{ $sizebtn }}')">
                                {{ $sizebtn }}
                            </button>
                            @endforeach
                        </div>

                        <!-- Hidden input to store selected size -->
                        <input type="hidden" name="size" id="selected-size">
                    </div>
                    <!-- price -->
                    <div class="mb-6">
                        <label for="price" class="block text-lg font-medium text-gray-700">
                            Price</label>
                        <input type="number" name="price" id="product-variant-price-input"
                            class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition">

                    </div>
                    <!-- stock -->
                    <div class="mb-6">
                        <label for="stock" class="block text-lg font-medium text-gray-700">
                            Stock</label>
                        <input type="number" name="stock" id="product-variant-stock-input"
                            class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-black focus:outline-none transition">

                    </div>
                    <!-- Discount Dropdown -->
                    <div class="mb-6">
                        <label for="discount_name"
                            class="block text-sm font-semibold text-gray-700 mb-1">Discount</label>
                        <select name="discount_id"
                            class="w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-black focus:outline-none text-sm">
                            <option value="">
                                {{ $product['product_variant'][0]['discount']['discount_name'] ?? 'No discount' }}
                            </option>
                            @foreach ($discounts as $discount)
                            <option value="{{ $discount['id'] }}">{{ $discount['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="color" class="block text-lg font-medium text-gray-700 mb-2">
                            Color
                        </label>
                        <div class="flex items-center space-x-4">
                            <!-- Color input container -->
                            <input type="color" name="color" id="product-variant-color-input"
                                class="w-12 h-12 rounded-full border border-gray-300 focus:ring-2 focus:ring-black focus:outline-none transition-all ease-in-out duration-150" />
                            <!-- Display the selected color with label -->
                            <span id="color-display" class="text-sm text-gray-600">#000000</span>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="image" class="block text-lg font-medium text-gray-700 mb-2">
                            Images
                        </label>
                        <div class="flex items-center space-x-4">
                            <!-- Image input container -->
                            <input type="file" name="images[]" id="product-variant-image-input"
                                class="w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-black focus:outline-none transition-all ease-in-out duration-150"
                                accept="image/*" multiple>


                            <!-- Display the selected images count with label -->
                            <span id="image-display" class="text-sm text-gray-600">No images selected</span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancel-edit-product-variant-btn"
                            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-black text-white rounded-md hover:bg-gray-800 transition">
                            Save
                        </button>

                </form>
            </div>
            </form>
            <!-- @if (session('success')) -->
            <!-- <div class="bg-green-200 text-green-800 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="bg-red-200 text-red-800 px-4 py-2 rounded">
                    {{ session('error') }}
                </div>
                @endif

                @if ($errors->any())
                <ul class="bg-red-100 text-red-700 px-4 py-2 rounded">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif -->

        </div>
    </div>




    </div>

    {{-- JavaScript --}}
    <script>
        // Get the file input and the display span
        const imageInput = document.getElementById('product-variant-image-input');
        const imageDisplay = document.getElementById('image-display');

        // Event listener to update the display text when user selects files
        imageInput.addEventListener('change', function(e) {
            const files = e.target.files;
            console.log(files);
            if (files.length > 0) {
                imageDisplay.textContent = `${files.length} image(s) selected`;
            } else {
                imageDisplay.textContent = 'No images selected';
            }


        });
        // Display selected color code next to the color input
        document.getElementById('product-variant-color-input').addEventListener('input', function(e) {
            document.getElementById('color-display').textContent = e.target.value.toUpperCase();
        });
        // Show the Edit Product Form Modal
        document.getElementById('edit-product-btn').addEventListener('click', () => {
            const formOverlay = document.getElementById('edit-product-form-overlay');
            const form = document.getElementById('edit-product-form');

            // Show the overlay and modal with smooth scaling animation
            formOverlay.classList.remove('hidden');
            form.classList.remove('scale-95');
            form.classList.add('scale-100');

            // Smooth scroll to the form
            window.scrollTo({
                top: form.offsetTop - 100,
                behavior: 'smooth'
            });
        });

        document.getElementById('cancel-edit-product-btn').addEventListener('click', () => {
            const formOverlay = document.getElementById('edit-product-form-overlay');
            const form = document.getElementById('edit-product-form');

            // Hide the form with smooth exit animation
            form.classList.remove('scale-100');
            form.classList.add('scale-95');

            setTimeout(() => {
                formOverlay.classList.add('hidden');
            }, 300); // Delay to allow smooth scaling out transition
        });
        // Show the Edit Product Variant Form Modal
        document.getElementById('edit-product-variant-btn').addEventListener('click', () => {
            const formOverlay = document.getElementById('edit-product-variant-form-overlay');
            const form = document.getElementById('edit-product-variant-form');
            document.getElementById('product-variant-price-input').value = selectedVariant.price
            document.getElementById('product-variant-stock-input').value = selectedVariant.stock
            // document.getElementById('product-variant-color-input').value = selectedVariant.color
            // document.getElementById('product-variant-size').value = selectedVariant.size;
            // document.getElementById('product-variant-discount_id').value = selectedVariant.discount_id;
            // document.getElementById('product-variant-discount_name').value = selectedVariant.discount ?
            // selectedVariant.discount.discount_name:
            //     'No discount';
            console.log('here is select variant', selectedVariant);
            const variantForm = document.getElementById('variant-form');
            variantForm.action = `/admin/updateProductVariant/${selectedVariant.id}`;
            variantForm.querySelector('input[name="_method"]').value = 'PUT';
            // Show the overlay and modal with smooth scaling animation
            formOverlay.classList.remove('hidden');
            form.classList.remove('scale-95');
            form.classList.add('scale-100');

            // Smooth scroll to the form
            window.scrollTo({
                top: form.offsetTop - 100,
                behavior: 'smooth'
            });
        });

        document.getElementById('cancel-edit-product-variant-btn').addEventListener('click', () => {
            const formOverlay = document.getElementById('edit-product-variant-form-overlay');
            const form = document.getElementById('edit-product-variant-form');

            // Hide the form with smooth exit animation
            form.classList.remove('scale-100');
            form.classList.add('scale-95');

            setTimeout(() => {
                formOverlay.classList.add('hidden');
            }, 300); // Delay to allow smooth scaling out transition
        });


        // });
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
                thumb.dataset.img = 'http://127.0.0.1:8000/storage/' + img.images;
                gallery.appendChild(thumb);
            });
        }

        function renderSizeButtons(color) {
            filtered = productVariants.filter(v => v.color === color);
            sizeContainer.innerHTML = '';

            filtered.forEach(variant => {
                const btn = document.createElement('button');
                btn.className =
                    'size-btn px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-100 hover:text-black transition';
                btn.textContent = variant.size;
                btn.dataset.variant = JSON.stringify(variant);

                btn.addEventListener('click', () => {
                    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('bg-black',
                        'text-white'));
                    btn.classList.add('bg-black', 'text-white');
                    updateDetail(variant);
                    selectedVariant = variant;


                    document.getElementById('color-display').textContent = selectedVariant.color
                        .toUpperCase();
                    document.getElementById('')
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


        function selectSizeBtn(button, sizeValue) {
            // Reset styles for all buttons
            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.style.backgroundColor = '';
                btn.style.borderColor = '';
                btn.style.color = '';
            });

            // Apply style to the clicked button
            button.style.borderColor = '#374151'; // Tailwind's border-gray-700
            button.style.color = '#111827'; // Tailwind's text-gray-900
            // Set hidden input value
            document.getElementById('selected-size').value = sizeValue;
        }
        document.getElementById('delete-product-variant-btn').addEventListener('click', function() {
            const selectedId = selectedVariant?.id;

            if (!selectedId) {
                Swal.fire('No Selection', 'Please select a product variant to delete.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete this product variant.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                console.log(selectedId);

                if (result.isConfirmed) {
                    fetch(`{{ route('admin.delete-product-variant') }}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: JSON.stringify({
                                id: selectedId
                            })

                        })
                        .then(res => {
                            if (!res.ok) throw new Error("Network response was not ok");
                            return res.json();


                        })
                        .then(data => {


                            if (data.message?.includes("success")) {
                                Swal.fire('Deleted!', 'Product variant deleted successfully.',
                                        'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'Failed to delete the product variant.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Something went wrong while deleting.', 'error');
                        });
                }
            });
        });
    </script>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    </x-layout>