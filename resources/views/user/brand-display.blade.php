<x-layout>
    <h1 class="text-2xl font-semibold  mb-12 mt-5  text-gray-900">All Brands</h1>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-10 ">
        @foreach ($brands as $brand)
        <a href="{{ route('productSort', ['type' => 'Brand :', 'brand' => $brand['id'], 'value' => $brand['brand_name']]) }}"
            class="flex flex-col items-center bg-white rounded-xl border border-gray-200 shadow-sm
                  hover:shadow-lg hover:scale-105 transition-transform duration-300 p-4 animate-fadeIn">
            <div class="w-full flex justify-center overflow-hidden rounded-lg">
                <img src="{{ asset($brand['image']) }}" alt="{{ $brand['brand_name'] }}"
                    class="w-full max-w-[300px] h-40 object-contain transition-transform duration-300 hover:scale-110" />
            </div>
            <span
                class="mt-4 text-lg font-semibold text-gray-800 hover:text-[#128B9E] hover:underline transition-colors duration-300 text-center">
                {{ $brand['brand_name'] }}
            </span>
        </a>
        @endforeach
    </div>

    <div class="mt-16 flex justify-center">
        {{ $brands->links('pagination::tailwind') }}
    </div>


    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease forwards;
        }
    </style>
</x-layout>