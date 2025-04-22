@php
$navLinks = [
['href' => '/productSort/?type:Men', 'name' => 'Men'],
['href' => '/productSort/?type:Women', 'name' => 'Women'],
['href' => '/productSort/?type:Child', 'name' => 'Child'],
['href' => '/productSort/?product:Discount', 'name' => 'Discount'],
];
$FollowUs = [
['href' => 'https://www.facebook.com/', 'name' => 'Facebook', 'icon' => asset('/icon/facebook-svgrepo-com.svg')],
['href' => 'https://www.instagram.com/', 'name' => 'Instagram', 'icon' => asset('/icon/instagram-svgrepo-com.svg')],
['href' => 'https://www.twitter.com/', 'name' => 'Twitter', 'icon' => asset('/icon/twitter-3-svgrepo-com.svg')],
['href' => 'https://www.youtube.com/', 'name' => 'YouTube', 'icon' => asset('/icon/youtube-168-svgrepo-com.svg')],
];
$ContactUs = [
['text' => 'Clothes@gmail.com', 'icon' => asset('/icon/email-open-svgrepo-com.svg')],
['text' => '+123456789', 'icon' => asset('/icon/phone-svgrepo-com.svg')],
['text' => 'Telegram', 'icon' => asset('/icon/telegram-svgrepo-com.svg')],
];

@endphp

<!-- Optional: Add this in your <head> to control fade animation -->
<style>
    .fade-out {
        animation: fadeOut 0.4s ease-out forwards;
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            visibility: hidden;
        }
    }

    /* Additional styles for smooth loader and content transition */
    #page-loader {
        transition: opacity 0.4s ease-out;
    }

    #main-content {
        transition: opacity 0.4s ease-out;
    }
</style>

<!doctype html>
<html>

<head>
    <title>Clothing</title>
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/x-icon" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="lg:px-0 xl:px-0 2xl:px-72 py-5">
    <nav class="flex justify-between items-center z-10">
        <div>
            <div class="flex space-x-16 px-5">
                @foreach($navLinks as $navLink)
                <x-nav-link href="{{ $navLink['href'] }}" :active="request()->is($navLink['href'])">
                    {{ $navLink['name'] }}
                </x-nav-link>
                @endforeach
            </div>
        </div>
        <div class="flex items-center justify-center   ">
            <a href="/"><img src={{ asset('logo.svg') }} alt=""></a>
        </div>
        <div class="flex gap-5">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197M15.803 15.803A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <!-- Trigger Input -->
                <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border rounded-lg w-full ring-0"
                    onclick="toggleSearchPopup()" />

                <!-- Search Popup -->
                <div id="searchPopup"
                    class="fixed top-0 left-0 w-full bg-white shadow-lg p-4 z-50 transform -translate-y-full transition-transform duration-300 ease-out">
                    <div class="max-w-3xl mx-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Search Products</h2>
                            <button onclick="toggleSearchPopup()"
                                class="text-gray-500 hover:text-black">&times;</button>
                        </div>
                        <input id="popupSearchInput" type="text" placeholder="Type to search..."
                            class="w-full px-4 py-2 border rounded-md" />
                        <div id="popupSearchResults" class="mt-4 space-y-2"></div>
                    </div>
                </div>

            </div>
            <div class="flex items-center justify-center gap-10">
                <!-- Favorite Button -->
                <button id="add-to-favorite-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </button>

                <!-- Add to Cart -->
                <button class="add-to-cart-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </button>
            </div>


            <!-- This will be shown/hidden -->
            <!-- Cart Tab -->
            <!-- Overlay for blur background -->
            <div id="cart-overlay" class="fixed inset-0 z-40 bg-black/10 backdrop-blur-sm hidden"></div>

            <!-- Cart tab -->
            <div id="cart-tab"
                class="fixed right-0 top-0 w-full sm:w-1/3 md:w-1/4 lg:w-1/5 h-full bg-white shadow-lg z-50 transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col py-6 border-l border-gray-300">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">🛒 Cart</h2>
                    <button id="close-cart-btn" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
                </div>
                <div id="cart-items" class="flex flex-col gap-4 overflow-y-auto max-h-[70vh]"></div>
                <div class="absolute bottom-0 p-4 w-full">
                    <form id="checkoutForm">
                        <button id="checkoutBtn" type="submit"
                            class="w-full bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                            Proceed to Checkout</button>
                    </form>

                </div>

            </div>


            <div class="space-x-10">
                @auth
                <button class="text-xl">LogOut</button>
                @endauth
                @guest
                <button class="text-xl text-slate-950 bg-gray-100 px-5 py-2 rounded-lg hover:shadow-xl"><a
                        href="/login">Login</a></button>
                <button class="text-xl text-white bg-slate-950 px-5 py-2 rounded-lg hover:shadow-sm"><a
                        href="/register">Register</a></button>
                @endguest
            </div>
    </nav>

    <main>
        <!-- Loader -->
        <div id="page-loader" class="flex justify-center items-center h-96">
            <div class="w-12 h-12 border-4 border-black border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Main Content Slot -->
        <div id="main-content" class="hidden opacity-0">
            {{ $slot }}
        </div>
    </main>

    <footer>
        <section class="flex justify-between items-start mt-28">
            <div>
                <img src={{ asset('logo.svg') }} alt="" />
            </div>
            <div class="flex space-x-10 space-y-1">
                <div>
                    <h3 href="" class="text-xl font-bold">Follow Us</h3>
                    @foreach($FollowUs as $FolowUs)
                    <a href="{{ $FolowUs['href'] }}" class="flex items-center space-x-4">
                        <img src="{{ $FolowUs['icon'] }}" alt="" class="w-6 h-6">
                        <p>{{ $FolowUs['name'] }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="flex flex-col space-y-1">
                    <h3 class="text-xl font-bold">Contact Us</h3>
                    @foreach($ContactUs as $contact)
                    <div class="flex items-center space-x-4">
                        <img src="{{ $contact['icon'] }}" alt="" class="w-6 h-6">
                        <p>{{ $contact['text'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="text-xl font-bold">WE ACCEPT</h3>
                <img src={{ asset('/images/We-accept-payment–for-web-footer-1.png') }} alt="" class="w-96">
            </div>
        </section>
        <div class="w-full h-0.5 bg-black my-10"></div>
        <section class="flex justify-end">
            <div>
                <p>© 2021 Clothes Store. All Rights Reserved.</p>
            </div>
        </section>
    </footer>
</body>