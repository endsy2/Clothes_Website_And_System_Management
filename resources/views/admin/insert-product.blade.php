<x-admin-layout>
    <!-- <span class="text-2xl font-semibold">Products</span> -->
    <form action={{ route('add-product') }} method="POST" enctype="multipart/form-data" id="form-input"
        class="space-y-5 bg-white py-6 px-10 rounded-lg shadow-md max-w-5xl mx-auto">
        @csrf
        <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Insert Product</h2>
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Basic Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <input type="text" name="name" placeholder="Product Name"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                <x-error-form name="name" />
            </div>

            <div>
                <input type="number" name="price" placeholder="Price"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                <x-error-form name="price" />
            </div>

            <div>
                <input type="number" name="stock" placeholder="Stock Quantity"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                <x-error-form name="stock" />
            </div>

            <div>
                <input type="text" name="description" placeholder="Description"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                <x-error-form name="description" />
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Classification</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <select name="category_id"
                    class="w-full bg-white border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category['id'] }}">{{ $category['category_name'] }}</option>
                    @endforeach
                </select>
                <x-error-form name="category_id" />
            </div>

            <div>
                <select name="brand_id"
                    class="w-full bg-white border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                    <option value="">Select Brand</option>
                    @foreach ($brands as $brand)
                    <option value="{{ $brand['id'] }}">{{ $brand['brand_name'] }}</option>
                    @endforeach
                </select>
                <x-error-form name="brand_id" />
            </div>

            <div>
                <select name="product_type_id"
                    class="w-full bg-white border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                    <option value="">Select Product Type</option>
                    @foreach ($productTypes as $productType)
                    <option value="{{ $productType['id'] }}">{{ $productType['product_type_name'] }}
                    </option>
                    @endforeach
                </select>
                <x-error-form name="product_type_id" />
            </div>

            <div>
                <select name="discount_id"
                    class="w-full bg-white border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black">
                    <option value="">Select Discount</option>
                    @foreach ($discounts as $discount)
                    <option value="{{ $discount['id'] }}">
                        {{ $discount['discount'] }}% - {{ $discount['discount_name'] }}
                        {{ $discount['discount_name'] }}
                    </option>
                    @endforeach
                </select>
                <x-error-form name="discount_id" />
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Variants</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Colors -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Colors</label>
                <div class="space-y-2" id="color-container">
                    <div class="flex items-center gap-2 color-input-wrapper">
                        <input type="color" name="color[]" class="h-10 w-10 rounded" required>
                        <button type="button" class="remove-color-btn text-red-600 font-semibold">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-color-btn"
                    class="mt-2 inline-block bg-black text-white text-sm px-3 py-1 rounded hover:bg-gray-800">
                    + Add Color
                </button>
            </div>

            <!-- Sizes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sizes</label>
                <div class="flex flex-wrap gap-2" id="size-wrapper">
                    @foreach(['S', 'M', 'L', 'XL', '2XL'] as $size)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="size[]" value="{{ $size }}" class="sr-only peer">
                        <div
                            class="px-4 py-2 border rounded-lg text-sm text-gray-700 peer-checked:bg-black peer-checked:text-white peer-checked:border-black transition">
                            {{ $size }}
                        </div>
                    </label>
                    @endforeach
                </div>
                <div id="size-error" class="text-red-500 text-sm hidden mt-1"></div>
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Images</h2>
        <div>
            <input type="file" id="images" name="images[]" multiple required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black">
            <x-error-form name="images" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <input type="submit" value="Add Product"
                class="bg-black text-white font-semibold px-6 py-2 rounded hover:bg-gray-900 transition cursor-pointer">
        </div>

        <!-- Messages -->
        <x-sucesss-form name="success" />
        <x-error-form name="error" />
    </form>
</x-admin-layout>

<script>
    document.getElementById('images').addEventListener('change', function(e) {
        const files = e.target.files;
        console.log('Number of images selected:', files.length);
        for (let i = 0; i < files.length; i++) {
            console.log(`Image ${i + 1}:`, files[i].name);

        }
    });
    document.getElementById('add-color-btn').addEventListener('click', () => {
        const wrapper = document.createElement('div');
        wrapper.classList.add('flex', 'items-center', 'gap-2', 'color-input-wrapper', 'mt-2');
        wrapper.innerHTML = `
            <input type="color" name="color[]" class="h-10 w-10 rounded" required>
            <button type="button" class="remove-color-btn text-red-600 font-semibold">Remove</button>
        `;
        document.getElementById('color-container').appendChild(wrapper);
    });

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-color-btn')) {
            e.target.parentElement.remove();
        }
    });
    document.querySelector('form').addEventListener('submit', function(e) {
        const files = document.querySelector('input[name="images[]"]').files;
        console.log('Selected files:', files);
    })
</script>