@props(['title', 'image','category_id'])

<a href="{{ route('productSort', ['type' => 'Category :', 'category_id' => $category_id,'value'=>$title]) }}">
    <div class="relative w-full h-72 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-top"
            style="background-image: url('https://my-app-files3.sgp1.digitaloceanspaces.com/{{ ltrim($image, '/') }}');">
        </div>
        <!-- Gradient Overlay & Title -->
        <div class="absolute bottom-0 left-0 w-full p-5 text-white font-semibold text-xl bg-gradient-to-t
            from-black/60 to-transparent z-10">
            <p>{{ $title }}</p>
        </div>
    </div>
</a>