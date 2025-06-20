@php
$links = [
['href' => '/admin/dashboard', 'name' => 'Dashboard'],
['href' => '/admin/product', 'name' => 'Product', 'sub' => [
['href' => 'product', 'name' => 'Product'],
['name' => 'Insert Product', 'href' => 'insertProduct'],
['name' => 'Insert Product Variant', 'href' => 'insertProductVariant'],
['name' => 'Insert Product Brand', 'href' => 'insertBrand'],
['name' => 'Insert Category', 'href' => 'insertCategory'],
['name' => 'Insert Product Type', 'href' => 'insertProductType']
]],
['href' => '/admin/discount*', 'name' => 'Discount', 'sub' => [
['href' => 'discount', 'name' => 'Discount'],
['name' => 'Insert Discount', 'href' => 'insertDiscount']
]],
['href' => '/admin/order', 'name' => 'Order'],
['href' => '/admin/user', 'name' => 'Customer'],
];
$currentUrl = request()->path();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" href="https://my-app-files3.sgp1.digitaloceanspaces.com/logo.svg" type="image/x-icon" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <title>Admin Panel</title>

    <style>
        /* Overlay for mobile sidebar */
        #sidebar-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">

        <!-- Mobile sidebar overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 bg-white w-64 border-r shadow-sm flex-col flex z-50 transform -translate-x-full md:translate-x-0 md:static md:flex transition-transform duration-300 ease-in-out overflow-y-auto">
            <div class="text-center py-6 text-xl font-bold text-black border-b">
                Admin Panel
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 text-sm">
                @foreach ($links as $link)
                @php
                $hasSub = isset($link['sub']);
                $isMainActive = request()->is(ltrim($link['href'], '/'));
                $isSubActive = false;
                if ($hasSub) {
                foreach ($link['sub'] as $subLink) {
                if (request()->is('admin/' . ltrim($subLink['href'], '/'))) {
                $isSubActive = true;
                break;
                }
                }
                }
                @endphp

                @if ($hasSub)
                <div class="space-y-1">
                    <button type="button" aria-expanded="{{ $isSubActive ? 'true' : 'false' }}"
                        class="w-full flex justify-between items-center px-4 py-2 rounded-lg font-medium transition
                                {{ $isMainActive || $isSubActive ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}" onclick="toggleSubmenu(this)">
                        <span>{{ $link['name'] }}</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300 {{ $isSubActive ? 'rotate-90' : '' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="pl-6 space-y-1 {{ $isSubActive ? '' : 'hidden' }}">
                        @foreach ($link['sub'] as $sub)
                        <a href="/admin/{{ $sub['href'] }}"
                            class="block px-3 py-1 rounded-md transition
                                        {{ request()->is('admin/' . ltrim($sub['href'], '/')) ? 'bg-black text-white' : 'hover:bg-gray-200 text-gray-700' }}">
                            {{ $sub['name'] }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @else
                <a href="{{ $link['href'] }}" class="block px-4 py-2 rounded-lg font-medium text-sm transition
                            {{ $isMainActive ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                    {{ $link['name'] }}
                </a>
                @endif
                @endforeach
            </nav>

            <form method="POST" action="/admin/logout" class="mt-auto px-4 py-4">
                @csrf
                <button type="submit"
                    class="w-full bg-black text-white py-2 hover:bg-gray-900 transition font-semibold text-center rounded-md">
                    Logout
                </button>
            </form>
        </aside>

        <!-- Hamburger menu for mobile -->
        <button id="hambar"
            class="fixed top-4 left-4 z-50 md:hidden p-2 rounded-md bg-white shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black"
            aria-label="Toggle menu" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
            </svg>
        </button>
        <div class="flex-1 relative ml-10 md:ml-15 transition-all duration-300 ease-in-out">
            <div id="page-loader"
                class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('logo.svg') }}" alt="Loading Logo" class="w-52 h-52 animate-pulse mb-4" />
                    <div class="loader-spinner"></div>
                </div>
            </div>

            <main id="main-content" class="opacity-0 transition-opacity px-10 py-5  duration-500">
                {{ $slot }}
            </main>
        </div>
    </div>

    @if (session('success'))
    <script>
        window.successMessage = @json(session('success'));
    </script>
    @endif

    <!-- External libraries -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.8/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- tailwind cdn -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Your custom scripts -->
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const hambar = document.getElementById('hambar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSubmenu(button) {
            const submenu = button.nextElementSibling;
            const arrow = button.querySelector('svg');

            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-90');
        }

        function toggleSidebar() {
            const isOpen = sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                hambar.setAttribute('aria-expanded', 'true');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                hambar.setAttribute('aria-expanded', 'false');
            }
        }
        hambar.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

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
            const loader = document.getElementById('page-loader');
            const mainContent = document.getElementById('main-content');

            // Add a delay before hiding the loader
            setTimeout(() => {
                loader.classList.add('opacity-0', 'pointer-events-none');
                mainContent.classList.remove('opacity-0');
                mainContent.classList.add('opacity-100'); // optional: smooth fade-in
            }, 1000); // 3000ms = 3 seconds
        });
    </script>
</body>

</html>