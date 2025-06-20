<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Clothing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="https://my-app-files3.sgp1.digitaloceanspaces.com/logo.svg" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- tailwind cdn -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>


<body>
    <nav class="border-b border-gray-300  lg:px-5 2xl:px-80 py-5 ">
        <div class="flex-shrink-0">
            <a href="/">
                <img src="https://my-app-files3.sgp1.digitaloceanspaces.com/logo.svg" alt="Logo"
                    class="h-5 md:h-7 xl:h-10 w-auto" />

            </a>
        </div>
    </nav>

    <body>

        <main>
            @yield('content')
        </main>
    </body>
</body>

</html>