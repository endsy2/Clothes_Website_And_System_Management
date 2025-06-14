<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cothing</title>



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
    @if(session('success'))
    <script>
        console.log('Success message:', @json(session('success')));

        window.successMessage = @json(session('success'));
    </script>
    @endif
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- tailwind cdn -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</body>

</html>