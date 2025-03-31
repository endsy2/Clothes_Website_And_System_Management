@props(['title', 'image'])
<div class="">
    <div class="relative w-full h-72 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-top" style="background-image: url('{{ $image }}');"></div>

        <!-- Gradient Overlay -->


        <!-- Content -->
        <a href="">
            <div
                class="absolute bottom-0 left-0 w-full p-5 text-white font-semibold text-xl bg-gradient-to-t from-black/60 to-transparent">
                <p>{{ $title }}</p>
            </div>
        </a>
    </div>
</div>