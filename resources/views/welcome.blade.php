@php
$category_data=[
['name'=>'LIFE STYLE','image'=>asset('images/khanh-tu-nguyen-huy-vLNEV1rIKYg-unsplash.jpg')],
['name'=>'SPORT LIFE','image'=>asset('images/shafiq-sah-XTFLl_G4biY-unsplash.jpg')],
['name'=>'SMART CASUAL','image'=>asset('images/frank-ching-mvd5ld3eOUU-unsplash.jpg')],
['name'=>'LIFE STYLE','image'=>asset('images/life_style.svg')],
['name'=>'SPORT LIFE','image'=>asset('images/sergio-contreras-DDaFDoyiOek-unsplash.jpg')],
['name'=>'SMART CASUAL','image'=>asset('images/vo-m-nh-d-c-UkdwOSKlMmQ-unsplash.jpg')],

]
@endphp
<x-layout>
    <x-slot name="title">
        <!-- Add your title here -->
    </x-slot>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center w-full h-[70vh] bg-no-repeat mt-5"
        style="background-image: url('{{ asset('images/slide1.svg') }}');">
        <div class="relative z-10 flex items-center h-full ml-36">
            <a href="#explore"
                class="px-10 py-5 bg-slate-950 text-3xl font-bold rounded-full shadow-lg hover:bg-slate-500 hover:scale-105 transition-all duration-300 text-white block text-center">
                SHOP NOW
            </a>
        </div>
    </section>


    <!-- Brand Section -->
    <!--HTML CODE-->

    <h2 class="font-semibold text-2xl pt-5">Trend Brand</h2>

    <p class="font-semibold text-lg  text-right"><a href="">More View</a></p>

    <div class=" w-full max-w-full mx-auto pt-5 ">
        <!-- Slick Slider -->
        <div class="brand-slider">
            @foreach ($brands as $brand)
            <a class="" href="">
                <!-- Add padding for spacing -->
                <div
                    class="bg-white rounded-2xl flex flex-col justify-center items-center py-3  shadow-lg hover:shadow-2xl transition duration-300">
                    <img src="{{ asset('/' . $brand->image) }}" alt="{{ $brand->brand_name }}"
                        class="w-40 h-40 object-contain rounded-lg">
                </div>
            </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-x-36 gap-y-10 mt-20">
            @foreach ($category_data as $element)

            <x-card-category :image="$element['image']" :title="$element['name']" />
            @endforeach
        </div>
    </div>
    <div class="grid  grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-x-36 gap-y-10 mt-20">
        <h2 class="font-semibold text-2xl">Discount</h2>

    </div>

    <!-- Additional Content Section (you can fill this later) -->
    <div class="h-[1000px]">
        <!-- Add more content here -->
    </div>
</x-layout>