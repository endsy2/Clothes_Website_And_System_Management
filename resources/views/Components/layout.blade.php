@php
$navLinks = [
['href' => '/?Product:Men', 'name' => 'Men'],
['href' => '/?Product:Women', 'name' => 'Women'],
['href' => '/?Product:Discount', 'name' => 'Discount'],
['href' => '/?Product:New_Arrival', 'name' => 'New Arrival'],
];
$FollowUs = [
['href' => 'https://www.facebook.com/', 'name' => 'Facebook', 'icon' => asset('/icon/facebook-svgrepo-com.svg')],
['href' => 'https://www.instagram.com/', 'name' => 'Instagram', 'icon' => asset('/icon/instagram-svgrepo-com.svg')],
['href' => 'https://www.twitter.com/', 'name' => 'Twitter', 'icon' => asset('/icon/twitter-3-svgrepo-com.svg')],

['href' => 'https://www.youtube.com/', 'name' => 'YouTube', 'icon' => asset('/icon/youtube-168-svgrepo-com.svg')],
];
$ContactUs=[
['text'=>'Clothes@gmail.com','icon'=>asset('/icon/email-open-svgrepo-com.svg')],
['text'=>'+123456789','icon'=>asset('/icon/phone-svgrepo-com.svg')],
['text' => 'Telegram', 'icon' => asset('/icon/telegram-svgrepo-com.svg')],
];
@endphp
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="bg-gray-100 px-32 py-5">
    <nav class="flex justify-between items-center z-10">
        <div>

            <div class="flex space-x-24">
                @foreach($navLinks as $navLink)
                <x-nav-link href="{{ $navLink['href'] }}" :active="request()->is($navLink['href'])">
                    {{ $navLink['name'] }}
                </x-nav-link>
                @endforeach
            </div>
        </div>
        <div>
            <a href="/"><img src={{ asset('logo.svg') }} alt=""></a>

        </div>
        <div class="flex space-x-10">
            <input type="text" placeholder="Search">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
        </div>
        <div class="space-x-10">
            @auth
            <button class=" text-xl">LogOut</button>
            @endauth
            @guest
            <button
                class="text-xl text-slate-950 bg-gray-100 px-5 py-2 rounded-lg hover:bg-slate-950 hover:text-white">Login</button>
            <button
                class="text-xl text-white bg-slate-950 px-5 py-2 rounded-lg hover:bg-gray-100 hover:text-slate-950">Register</button>
            @endguest
        </div>
    </nav>
    <main>

        <div>
            {{ $slot }}
        </div>
    </main>
    <footer>
        <section class="flex justify-between items-start mt-10">
            <div>
                <img src={{ asset('logo.svg') }} alt="">
            </div>
            <div class="flex space-x-10 space-y-1">
                <div>
                    <h3 href="" class="text-xl font-bold">Follow Us</h3>
                    @foreach($FollowUs as $FolowUs)
                    <a href="{{ $FolowUs['href'] }}" class="flex items-center space-x-4">

                        <img src="{{ $FolowUs['icon'] }}" alt="" class="w-6 h-6">
                        <p>{{ $FolowUs['name'] }}</p>
                    </a>
                    @endforeach
                </div>

            </div>
            <div>
                <div class="flex flex-col space-y-1">
                    <h3 class=" text-xl font-bold">Contact Us</h3>
                    @foreach($ContactUs as $contact)
                    <div class="flex  items-center space-x-4">
                        <img src="{{ $contact['icon'] }}" alt="" class="w-6 h-6">
                        <p>{{ $contact['text'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="text-xl font-bold ">WE ACCEPT</h3>
                <img src={{ asset('/images/We-accept-payment–for-web-footer-1.png') }} alt="" class="w-96">
            </div>
        </section>
        <div class="w-full h-0.5 bg-black my-10"></div>
        <section class="flex justify-end">
            <div>
                <p>© 2021 Clothes Store. All Rights Reserved.</p>
            </div>

    </footer>
</body>

</html>