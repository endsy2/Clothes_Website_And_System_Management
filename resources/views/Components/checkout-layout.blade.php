<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
@vite('resources/css/app.css')

<body>
    <nav class="border-b border-gray-300  lg:px-5 2xl:px-80 py-5 ">
        <img src="{{ asset('logo.svg') }}" alt="Logo" class="pt-4">
    </nav>

    <body>

        <main>
            @yield('content')
        </main>
    </body>
</body>

</html>