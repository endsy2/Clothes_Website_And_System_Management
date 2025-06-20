@php
$titles = ['Id', 'name', 'category', 'brand', 'price'];
@endphp

<!-- CSRF token for JavaScript -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<x-admin-layout>
    <span class="text-2xl font-semibold">Products</span>



    <!-- Add Product Form -->


    <div class="overflow-x-auto max-w-full bg-white shadow-md rounded-lg mt-8">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-xs text-gray-700 uppercase sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 w-10">
                        <input type="checkbox" id="select-all-checkbox" class="form-checkbox text-blue-500">
                    </th>
                    @foreach ($titles as $title)
                    <th class="px-6 py-3 font-semibold tracking-wide">{{ $title }}</th>
                    @endforeach
                    <th class="px-6 py-3 font-semibold tracking-wide text-center">
                        <button type="button" id="delete-selected-btn"><svg xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 ">

                @foreach($products['data'] as $product)
                @php $variant = $product['product_variant'][0] ?? null; @endphp
                <tr class="hover:bg-gray-50 transition-all ease-in-out duration-200">
                    <!-- Make the entire row clickable except for the delete button -->
                    <td class="px-6 py-4">
                        <input type="checkbox" name="selected_products[]" value="{{ $product['id'] }}"
                            class="product-checkbox text-blue-600">
                    </td>
                    <td class="px-6 py-4">
                        <a href="/admin/product/{{ $product['id'] }}"
                            class="block text-gray-900 hover:text-blue-600 font-medium">
                            {{ $product['id'] }}
                        </a>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        <a href="/admin/product/{{ $product['id'] }}" class="block text-gray-900 hover:text-blue-600">
                            {{ $product['name'] }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        <a href="/admin/product/{{ $product['id'] }}" class="block text-gray-700 hover:text-blue-600">
                            {{ $product['category']['category_name'] }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        <a href="/admin/product/{{ $product['id'] }}" class="block text-gray-700 hover:text-blue-600">
                            {{ $product['brand']['brand_name'] }}
                        </a>
                    </td>
                    <td class="px-6 py-4 font-semibold text-green-600">
                        <a href="/admin/product/{{ $product['id'] }}" class="block text-green-600 hover:text-blue-600">
                            {{ $variant ? '$' . number_format($variant['price'], 2) : 'N/A' }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="/admin/product/{{ $product['id'] }}" class="text-blue-600 hover:underline font-medium">
                            Edit
                        </a>
                        <!-- Better design for the delete button -->
                        <button class="text-red-600 hover:text-red-800 font-medium delete-btn"
                            data-id="{{ $product['id'] }}">
                            <span class="hidden sm:inline">Delete</span>
                            <span class="sm:hidden">🗑️</span> <!-- Emoji for mobile view -->
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>


        </table>

        <!-- Pagination -->
        <div class="p-4 border-t flex justify-center space-x-1 bg-white">
            @foreach ($products['links'] as $link)
            @if ($link['url'])
            <a href="{{ $link['url'] }}"
                class="px-3 py-1 border rounded text-sm {{ $link['active'] ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                {!! $link['label'] !!}
            </a>
            @else
            <span class="px-3 py-1 border rounded text-sm text-gray-400 cursor-not-allowed">{!! $link['label']
                !!}</span>
            @endif
            @endforeach
        </div>
    </div>

</x-admin-layout>

<!-- JavaScript -->
<script>
    // ✅ Handle Select All
    document.getElementById('select-all-checkbox').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = checked);
    });

    // ✅ Handle Bulk Delete
    document.getElementById('delete-selected-btn').addEventListener('click', function() {
        const selected = [...document.querySelectorAll('.product-checkbox:checked')].map(cb => cb.value);
        if (selected.length === 0) {
            Swal.fire('No Selection', 'Please select at least one product to delete.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selected.length} products.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                fetch('/admin/delete-products-many', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            ids: selected
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.message === "Products deleted successfully") {
                            Swal.fire('Deleted!', `${data.deleted} products deleted.`, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', 'Failed to delete products.', 'error');
                        }
                    });
                console.log('here is delete many');

            }
        });
    });

    // ✅ Handle Single Delete
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {


            Swal.fire({
                title: 'Are you sure?',
                text: 'This product will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                const productId = this.dataset.id;
                if (result.isConfirmed) {
                    fetch('/admin/delete-product-one', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                id: productId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.message === "Product deleted successfully") {
                                Swal.fire('Deleted!', 'Product has been deleted.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'Failed to delete product.', 'error');
                            }
                        });


                }
            });
        });
    });
</script>