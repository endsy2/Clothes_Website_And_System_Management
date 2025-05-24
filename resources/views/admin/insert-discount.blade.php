<x-admin-layout>
    <!-- <span class="text-2xl font-semibold">Products</span> -->
    <form action={{ route('admin.insertDiscount') }} method="POST" enctype="multipart/form-data" id="form-input"
        class="space-y-5 bg-white py-6 px-10 rounded-lg shadow-md max-w-5xl mx-auto">
        @csrf

        <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Insert Discount</h2>
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Basic Information</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div>
                <label>Discount Name</label>
                <input type="text" name="discount_name" placeholder="Discount Name"
                    class="w-full border border-gray-300 rounded  px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                <x-error-form name="discount_name" />
            </div>
            <div>
                <label>Discount Percentage</label>
                <input type="number" name="discount" placeholder="Discount "
                    class="w-full border border-gray-300 rounded  px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required min="0" max="100">
                <x-error-form name="discount" />
            </div>
            <div>
                <label>Start Date</label>
                <input type="date" name="start_date" placeholder="Start Date"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                <x-error-form name="start_date" />
            </div>
            <div>
                <label>End Date</label>
                <input type="date" name="end_date" placeholder="End Date"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                    required>
                <x-error-form name="end_date" />
            </div>
            <input type="submit" value="Add Discount"
                class="w-full bg-black text-white py-2 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
            <x-error-form name="brand_name" />
    </form>
</x-admin-layout>