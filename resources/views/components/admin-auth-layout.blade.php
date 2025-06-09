<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cothing</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <nav class="border-b  lg:px-5 2xl:px-80 py-5">
        <div>
            <a>
                <img class="pl-10" src="{{ asset('logo.svg') }}" alt="Logo" class="pt-4">
            </a>
        </div>
    </nav>

    <main class="flex justify-center ">
        @yield("content")
    </main>
</body>

</html>