@php
$links = [
['href' => '/admin/dashboard', 'name' => 'Dashboard'],
['href' => '/admin/product', 'name' => 'Product'],
['href' => '/admin/order', 'name' => 'Order'],
['href' => '/admin/user', 'name' => 'User'],
['href' => '/admin/discount', 'name' => 'Discount'],
['href' => '/admin/report', 'name' => 'Report']
];

$currentUrl = request()->path(); // get current route
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="bg-white w-1/6 border-r shadow-sm flex flex-col">
            <div class="text-center py-6 text-xl font-bold text-black border-b">
                Admin Panel
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                @foreach ($links as $link)
                @php
                $isActive = request()->is(ltrim($link['href'], '/'));
                @endphp

                <a href="{{ $link['href'] }}" class="block px-4 py-2 rounded-lg font-medium text-sm transition duration-200
                            {{ $isActive ? 'bg-black text-white' : 'hover:bg-[#f1f5f9] text-gray-700' }}">
                    {{ $link['name'] }}
                </a>
                @endforeach
            </nav>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-10">
            {{ $slot }}
        </main>
    </div>
</body>

</html>