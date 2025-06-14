<x-admin-layout>
    <!-- <span class="text-2xl font-semibold">Products</span> -->
    <form action={{ route('admin.insertProductType') }} method="POST" enctype="multipart/form-data" id="form-input"
        class="space-y-5 bg-white py-6 px-10 rounded-lg shadow-md max-w-5xl mx-auto">
        @csrf

        <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Insert Product Type</h2>
        <div class="grid grid-cols-1 gap-5">


            <input type="text" name="product_type_name" placeholder="Product Type Name"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                required>

            <input type="submit" value="Add Product Type"
                class="w-full bg-black text-white py-2 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
            <x-error-form name="product_type" />
    </form>
</x-admin-layout>