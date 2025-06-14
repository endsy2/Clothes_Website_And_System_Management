<x-admin-layout>
    <form action="{{ route('admin.editDiscount', request()->route('id')) }}" method="POST" enctype="multipart/form-data"
        id="form-input" class="space-y-5 bg-white py-6 px-10 rounded-lg shadow-md max-w-5xl mx-auto">
        @csrf
        @method('PUT')

        <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Edit Display</h2>

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <input type="text" name="discount_name" value="{{ $discount['discount_name'] }}" placeholder="Discount Name"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                required>
            @error('discount_name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <input type="number" name="discount" value="{{ $discount['discount'] }}" placeholder="Discount"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                required>
            @error('discount')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <input type="date" name="start_date" value="{{ $discount['start_date'] }}" placeholder="Start Date"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                required>
            @error('start_date')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <input type="date" name="end_date" value="{{$discount['end_date']  }}" placeholder="End Date"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-black"
                required>
            @error('end_date')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <input type="submit" value="Update Discount"
                class="w-full bg-black text-white py-5 rounded-lg font-semibold hover:bg-gray-700 transition duration-200">
        </div>
    </form>
</x-admin-layout>