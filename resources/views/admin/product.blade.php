@php
$titles = ['Id', 'name', 'category', 'brand', 'price'];
@endphp
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-admin-layout>
    <span class="text-2xl font-semibold">Insert New Product</span>
    <form action="/admin/add-product" id="form-input" class="grid grid-cols-2 gap-5 my-6 " method="POST"
        enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" placeholder="Product Name" class="border border-gray-300 rounded px-2 py-1 "
            required>
        <x-error-form name="name" />
        <select name="category_name" id="" class="bg-white border border-gray-300 rounded px-2 py-1" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
            <option value="{{ $category['category_name'] }}">
                {{ $category['category_name'] }}
            </option>
            @endforeach
        </select>
        <x-error-form name="category_name" />
        <select name="brand_name" id="" class="bg-white border border-gray-300 rounded px-2 py-1" required>
            <option value="">Select Brand</option>
            @foreach ($brands as $brand )
            <option value={{ $brand['brand_name'] }}>{{ $brand['brand_name'] }}</option>
            @endforeach

        </select>
        <x-error-form name="brand_name" />
        <div class="flex gap-2" id="color-container">
            <!-- Initial Color Input -->
            <div class="color-input-wrapper">
                <input type="color" name="color[]" class="border rounded px-2 py-1" required>
                <button type="button" class="remove-color-btn text-red-500 ml-2">X</button>
            </div>
        </div>

        <!-- Add Color Button -->

        <!-- Sizes -->
        <div class="flex gap-2" id="size-wrapper">
            @foreach(['S', 'M', 'L', 'XL', '2XL'] as $size)
            <label class="cursor-pointer">
                <input type="checkbox" name="size[]" value="{{ $size }}" class="sr-only peer">
                <div
                    class="px-4 py-2 border rounded-lg text-gray-700 peer-checked:bg-black peer-checked:text-white peer-checked:border-black transition">
                    {{ $size }}
                </div>

            </label>
            @endforeach
            <div id="size-error" class="hidden "></div>
        </div>


        <!-- <x-error-form name="size" /> -->
        <button type="button" id="add-color-btn" class="bg-black text-white px-4 py-2 rounded mt-4">
            Add Color
        </button>

        <input type="text" name="price" placeholder="price" class="border rounded border-gray-300 px-2 py-1" required>
        <x-error-form name="price" />
        <select name="discount" id="" class="bg-white border border-gray-300 rounded px-2 py-1">
            <option value="">Select Discount</option>
            @foreach ($discounts as $discount)
            <option value="{{ $discount['discount_name'] }}" @if(old('discount')==$discount['discount_name']) selected
                @endif>
                {{ $discount['discount_name'] }}
            </option>
            @endforeach
        </select>
        <x-error-form name="discount" />
        <input type="text" name="stock" placeholder="quantity" class="border rounded border-gray-300 px-2 py-1"
            required>
        <x-error-form name="stock" />
        <input type="text" name="description" placeholder="description" required
            class="border rounded border-gray-300 px-2 py-1">
        <x-error-form name="description" />
        <div class="flex flex-col gap-3">
            <input type="file" name="images[]" placeholder="image" class="border rounded px-2 border-gray-300 py-1"
                required multiple>
            <input type="submit" value="Add Product"
                class="bg-black text-white px-4 py-2 rounded mt-4 cursor-pointer hover:bg-gray-900 transition">
        </div>


        <x-error-form name="images" />
        <x-sucesss-form name="success" />
        <x-error-form name="error" />
    </form>
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Products</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($titles as $title)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $title }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products['data'] as $product)
                @php
                $productVariant = $product['product_variant'][0] ?? null;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-700">{{ $product['id'] }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $product['name'] }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $product['category']['category_name'] }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $product['brand']['brand_name'] }}</td>
                    <td class="px-6 py-4 text-gray-700">
                        @if ($productVariant)
                        ${{ number_format($productVariant['price'], 2) }}
                        @else
                        N/A
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 flex justify-center space-x-1">
            @foreach ($products['links'] as $link)
            @if ($link['url'])
            <a href="{{ $link['url'] }}"
                class="px-3 py-1 border rounded {{ $link['active'] ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                {!! $link['label'] !!}
            </a>
            @else
            <span class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed">{!! $link['label'] !!}</span>
            @endif
            @endforeach
        </div>

    </div>
</x-admin-layout>
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    window.successMessage = @json(session('success'));
</script>
@endif -->





<script>
    const addColorBtn = document.getElementById('add-color-btn');
    const colorContainer = document.getElementById('color-container');

    addColorBtn.addEventListener('click', () => {
        const newColorInput = document.createElement('div');
        newColorInput.classList.add('color-input-wrapper', 'flex', 'items-center', 'gap-2');

        newColorInput.innerHTML = `
            <input type="color" name="color[]" class="border rounded px-2 py-1" required>
            <button type="button" class="remove-color-btn text-red-500">X</button>
        `;

        colorContainer.appendChild(newColorInput);
    });

    colorContainer.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-color-btn')) {
            event.target.parentElement.remove();
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('form-input');
        const sizeWrapper = document.getElementById('size-wrapper');
        const sizeError = document.getElementById('size-error');

        form.addEventListener('submit', function(e) {
            const checkboxes = sizeWrapper.querySelectorAll('input[name="size[]"]');
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);

            if (!anyChecked) {
                e.preventDefault(); // Prevent form submission
                sizeError.innerText = 'Please select at least one size.';
                sizeError.classList.remove('hidden');
                sizeError.classList.add('text-red-500', 'font-semibold', 'mt-1', 'text-center');
            } else {
                sizeError.classList.add('hidden');
                sizeError.innerText = '';
            }
        });

    });
</script>