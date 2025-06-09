@php
$links = [
['href' => '/admin/dashboard', 'name' => 'Dashboard'],
['href' => '/admin/product', 'name' => 'Product','sub'=>[['href' => 'product', 'name' =>
'Product'],['name'=>"Insert
Product",'href'=>'insertProduct'],['name'=>'Insert Product Variant','href'=>'insertProductVariant'],['name'=>'Insert
Product Brand','href'=>'insertBrand'],['name'=>'Insert Category','href'=>'insertCategory'],['name'=>'Insert
Product
Type','href'=>'insertProductType']]],
['href' => '/admin/discount*', 'name' => 'Discount','sub'=>[['href' => 'discount', 'name' =>
'Discount'],['name'=>"Insert Discount",'href'=>'insertDiscount']]],
['href' => '/admin/order', 'name' => 'Order'],
['href' => '/admin/user', 'name' => 'Customer'],


];
$currentUrl = request()->path();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- tailwind cdn -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Admin Panel</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/loading.js')
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="bg-white w-64 border-r shadow-sm  flex-col hidden md:flex" id="sidebar">
            <div class="text-center py-6 text-xl font-bold text-black border-b">
                Admin Panel
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 text-sm">
                @foreach ($links as $link)
                @php
                $hasSub = isset($link['sub']);
                $isMainActive = request()->is(ltrim($link['href'], '/'));
                @endphp

                @if ($hasSub)
                <div class="space-y-1">
                    <button type="button" class="w-full flex justify-between items-center px-4 py-2 rounded-lg font-medium transition
                    {{ $isMainActive ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}"
                        onclick="toggleSubmenu(this)">
                        <span>{{ $link['name'] }}</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="pl-6 space-y-1 hidden">
                        @foreach ($link['sub'] as $sub)
                        <a href="/admin/{{ $sub['href'] }}"
                            class="block px-3 py-1 rounded-md transition hover:bg-gray-200 text-gray-700">
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

            <form method="POST" action="/admin/logout" class="mt-auto">
                @csrf
                <button type="submit"
                    class="w-full bg-black text-white py-2 hover:bg-gray-900 transition font-semibold text-center">
                    Logout
                </button>
            </form>

        </aside>
        <aside id="hambar" class="px-5 py-10 md:hidden bg-white w-full border-b shadow-sm ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
            </svg>
        </aside>

        <!-- Main content with loader -->
        <div class="flex-1 relative">
            <div id="page-loader"
                class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('logo.svg') }}" alt="Loading Logo" class="w-52 h-52'' animate-pulse mb-4">
                    <div class="loader-spinner"></div>
                </div>
            </div>


            <main id="main-content" class="opacity-0 transition-opacity duration-500 ">
                {{ $slot }}
            </main>
        </div>
    </div>
    @if(session('success'))
    <script>
        window.successMessage = @json(session('success'));
    </script>
    @endif

</body>

</html>

<script>
    const sidebar = document.getElementById('sidebar');
    const hambar = document.getElementById('hambar');

    function toggleSubmenu(button) {
        const submenu = button.nextElementSibling;
        const arrow = button.querySelector('svg');

        // Toggle the 'hidden' class to show or hide the submenu
        submenu.classList.toggle('hidden');
        // Toggle the rotation of the arrow when clicked
        arrow.classList.toggle('rotate-90');
    }
</script>