<x-admin-layout>
    <!-- <span class="text-2xl font-semibold">Products</span> -->
    <form action={{ route('admin.insertCategory') }} method="POST" enctype="multipart/form-data" id="form-input"
        class="space-y-5 bg-white py-6 px-10 rounded-lg shadow-md max-w-5xl mx-auto">
        @csrf

        <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Insert Category</h2>
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Basic Information</h2>
        <div class="grid grid-cols-1 gap-5">

            <input type="text" name="category_name" placeholder="Category Name"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                required>
            <input type="file" name="image" id="image"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                required>
            <input type="submit" value="Add Category"
                class="w-full bg-black text-white py-2 rounded-lg font-semibold hover:bg-gray-800 transition duration-200">
            <x-error-form name="brand_name" />
    </form>
</x-admin-layout>