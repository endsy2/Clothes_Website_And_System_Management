<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
</head>

<body>
    <nav>
        <x-nav-link href="/home" :active="request()->is('home')">Home</x-nav-link>
        <x-nav-link href="/about" :active="request()->is('about')">About</x-nav-link>
        <x-nav-link href=" /service" :active="request()->is('service')">Service</x-nav-link>

    </nav>
    <main>
        <div>
            <h1>{{ $title }}</h1>
            {{ $slot }}
        </div>
    </main>
    <footer>
        it footer
    </footer>
</body>

</html>