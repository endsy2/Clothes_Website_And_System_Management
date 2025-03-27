<x-layout>
    <x-slot name="title">

    </x-slot>
    <div class="flex items-center justify-center ">
        <div class="absolute top-0 w-[90%] h-screen bg-cover bg-center z-[-1]"
            style="background-image: url('{{ asset('images/slide1.svg') }}'); object-fit: cover;">

            <!-- Optional Overlay (Dark Background) to Improve Text Visibility -->


            <!-- Centered Content -->
            <button
                class="absolute top-[580px] left-60 transform -translate-x-1/2 px-6 py-3 bg-slate-950 text-xl rounded-lg hover:bg-slate-600 hover:scale-105 transition-all duration-300 text-white">
                <a href="#explore" class="block text-center">
                    Explore Now
                </a>
            </button>


        </div>
    </div>

    <div class=" h-[1000px]">
    </div>

</x-layout>