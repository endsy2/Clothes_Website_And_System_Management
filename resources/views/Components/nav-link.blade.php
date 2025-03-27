@props(['active' => false, 'href'])

<a class="text-lg " href="{{ $href }}">
    {{ $slot }}
</a>