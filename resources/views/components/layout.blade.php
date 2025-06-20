@php
$navLinks = [
[
'name' => 'Men',
'type' => 'Product Type',
'value' => 'Men',
'products_type_id' => 1,
],
[
'name' => 'Women',
'type' => 'Product Type',
'value' => 'Women',
'products_type_id' => 2,
],
[
'name' => 'Child',
'type' => 'Product Type',
'value' => 'Child',
'products_type_id' => 3,
],
[
'name' => 'Discount',
'type' => 'discount', // or whatever your controller expects
'value' => 'Discount',
'products_type_id' => null,
'product' => 'Discount', // if your controller looks for `product`
],
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

    #page-loader,
    #main-content {
        transition: opacity 0.4s ease-out;
    }
</style>

<!doctype html>
<html>

<head>
    <title>Clothing Store</title>
    <link rel="icon" href="https://my-app-files3.sgp1.digitaloceanspaces.com/logo.svg" type="image/x-icon" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- tailwind cdn -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script type="module" src="/build/assets/app-D-t_snuq.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Bar -->
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/">
                        <img src="https://my-app-files3.sgp1.digitaloceanspaces.com/logo.svg" alt="Logo"
                            class="h-5 md:h-7 xl:h-10 w-auto" />

                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-8">
                    @foreach($navLinks as $navLink )

                    <a href="{{ route('productSort', ['value' => $navLink['value'],'type' => $navLink['type'],'products_type_id' => $navLink['products_type_id']]) }}"
                        class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors duration-200">
                        {{ $navLink['name'] }}
                    </a>

                    @endforeach
                </nav>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative hidden sm:block">
                        <input type="text" placeholder="Search products..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            onclick="toggleSearchPopup()" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        <!-- Favorites -->
                        <a href="/add-to-favorite"
                            class="p-2 text-gray-700 hover:text-red-500 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </a>

                        <!-- Cart -->
                        <button
                            class="add-to-cart-btn p-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-lg transition-colors duration-200 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </button>

                        <!-- Auth Buttons -->
                        @auth('customer')
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                Logout
                            </button>
                        </form>
                        @endauth

                        @guest('customer')
                        <a href="/login" class="text-gray-700 hover:text-gray-900 px-3 py-2 font-medium">Login</a>
                        <a href="/register"
                            class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors duration-200">Register</a>
                        @endguest
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2 text-gray-700 hover:text-gray-900" onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    @foreach($navLinks as $navLink)
                    <a href="{{ route('productSort', ['value'=>$navLink['value'],'type'=>$navLink['type'],'products_type_id'=>$navLink['products_type_id']])  }}""
                        class=" block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md">
                        {{ $navLink['name'] }}
                    </a>
                    @endforeach
                </div>
                <!-- Mobile Search -->
                <div class="px-2 pb-3">
                    <input type="text" placeholder="Search products..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onclick="toggleSearchPopup()" />
                </div>
            </div>
        </div>
    </header>

    <!-- Search Popup -->
    <div id="searchPopup"
        class="fixed top-0 left-0 w-full h-full bg-white z-50 transform -translate-y-full transition-transform duration-300 ease-out">
        <div class="max-w-4xl mx-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Search Products</h2>
                <button onclick="toggleSearchPopup()"
                    class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <input id="popupSearchInput" type="text" placeholder="Type to search..."
                class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            <div id="popupSearchResults" class="mt-6 space-y-4"></div>
        </div>
    </div>

    <!-- Cart Overlay -->
    <div id="cart-overlay" class="fixed inset-0 z-40 backdrop-blur-sm bg-opacity-50 hidden"></div>

    <!-- Cart Sidebar -->
    <div id="cart-tab"
        class="fixed right-0 top-0 w-full sm:w-96 h-full bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col rounded-l-lg overflow-hidden">

        <!-- Header -->
        <header class="flex justify-between items-center px-6 py-5 border-b border-gray-300 bg-gray-50">
            <h2 class="text-2xl font-bold text-gray-900 tracking-wide">Shopping Cart</h2>
            <button id="close-cart-btn" aria-label="Close Cart"
                class="text-gray-600 hover:text-gray-900 transition-colors duration-300 text-3xl font-extrabold leading-none">&times;</button>
        </header>

        <!-- Cart Items -->
        <div id="cart-items"
            class="flex-1 overflow-y-auto px-6 py-5 space-y-5 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
        </div>

        <!-- Footer -->
        <footer class="p-6 border-t border-gray-300 bg-white">
            <a href="/checkout"
                class="block w-full bg-gradient-to-r from-gray-900 to-black text-white text-center py-4 rounded-xl shadow-lg hover:from-gray-800 hover:to-gray-900 transition-colors duration-300 font-semibold tracking-wide">
                Proceed to Checkout
            </a>
        </footer>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        <!-- Loader -->
        <div id="page-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
            <div class="text-center">
                <img src="{{ asset('logo.svg') }}" alt="Loading" class="w-40 h-20 mx-auto mb-4 animate-pulse">
            </div>
        </div>

        <!-- Content -->
        <div id="main-content" class="hidden opacity-0 transition-opacity duration-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-va= text-black mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="space-y-4">
                    <img src="{{ asset('logo.svg') }}" alt="Logo" class="h-5 md:h-7 xl:h-10 w-auto " />
                </div>

                <!-- Follow Us -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Follow Us</h3>
                    <div class="space-y-3">
                        @foreach($FollowUs as $social)
                        <a href="{{ $social['href'] }}"
                            class="flex items-center space-x-3 text-black hover:text-gray-800 transition-colors duration-200">
                            <img src="{{ $social['icon'] }}" alt="{{ $social['name'] }}" class="w-5 h-5 ">
                            <span>{{ $social['name'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Contact -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Contact Us</h3>
                    <div class="space-y-3">
                        @foreach($ContactUs as $contact)
                        <div class="flex items-center space-x-3 text-black">
                            <img src="{{ $contact['icon'] }}" alt="" class="w-5 h-5  ">
                            <span>{{ $contact['text'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">We Accept</h3>
                    <img src="{{ asset('/images/We-accept-payment–for-web-footer-1.png') }}" alt="Payment Methods"
                        class="max-w-full h-auto">
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col sm:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">© 2024 Clothes Store. All Rights Reserved.</p>
                <div class="flex space-x-6 mt-4 sm:mt-0">
                    <a href="#"
                        class="text-black hover:text-gray-800 text-sm transition-colors duration-200">Privacy</a>
                    <a href="#" class="text-black hover:text-gray-800 text-sm transition-colors duration-200">Terms</a>
                    <a href="#"
                        class="text-black hover:text-gray-800 text-sm transition-colors duration-200">Support</a>
                </div>
            </div>
        </div>
    </footer>
    @if (session('success'))
    <script>
        window.successMessage = @json(session('success'));
    </script>
    @endif

    <script>
        console.log(localStorage);
        window.addEventListener('beforeunload', () => {
            localStorage.setItem('showCart', 'false');
        });

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (window.successMessage) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: window.successMessage,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'bg-white text-green-800 shadow-lg border-l-4 border-green-500 px-4 py-3 rounded-md'
                    },
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

            }
            // Hide loader and show content after page load
            const loader = document.getElementById('page-loader');
            const mainContent = document.getElementById('main-content');

            // Add a delay before hiding the loader
            setTimeout(() => {
                loader.classList.add('opacity-0', 'pointer-events-none');
                mainContent.classList.remove('opacity-0');
                mainContent.classList.remove('hidden');
                mainContent.classList.add('opacity-100'); // optional: smooth fade-in
            }); // 3000ms = 3 seconds

        });
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/addToCartTab.js') }}"></script>
    <script src="{{ asset('js/add-to-favorite.js') }}"></script>
    <script src="{{ asset('js/slider.js') }}"></script>
</body>

</html>