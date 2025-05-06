@php
$links = [
['href' => '/admin/dashboard', 'name' => 'Dashboard'],
['href' => '/admin/product', 'name' => 'Product'],
['href' => '/admin/order', 'name' => 'Order'],
['href' => '/admin/user', 'name' => 'Customer'],
['href' => '/admin/discount', 'name' => 'Discount'],
['href' => '/admin/report', 'name' => 'Report']
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

    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/adminLoading.js','resources/js/alert.js'])
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="bg-white w-64 border-r shadow-sm  flex-col hidden md:flex" id="sidebar">
            <div class="text-center py-6 text-xl font-bold text-black border-b">
                Admin Panel
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                @foreach ($links as $link)
                @php
                $isActive = request()->is(ltrim($link['href'], '/'));
                @endphp
                <a href="{{ $link['href'] }}" class="block px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200
                        {{ $isActive ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                    {{ $link['name'] }}
                </a>
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
            <div id="page-loader" class="absolute inset-0 flex justify-center items-center bg-white z-10">
                <div class="w-12 h-12 border-4 border-black border-t-transparent rounded-full animate-spin"></div>
            </div>

            <main id="main-content" class="opacity-0 transition-opacity duration-500 p-10">
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

    const
</script>