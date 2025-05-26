<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Clothing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
@vite('resources/css/app.css')

<body>
    <nav class="border-b border-gray-300  lg:px-5 2xl:px-80 py-5 ">
        <a href="/">

            <img src="{{ asset('logo.svg') }}" alt="Logo" class="pt-4">
        </a>
    </nav>

    <body>

        <main>
            @yield('content')
        </main>
    </body>
</body>

</html>