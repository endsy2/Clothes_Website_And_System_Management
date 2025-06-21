@props(['active' => false, 'href'])

<a class="text-lg font-semibold " href="{{ url($href) }}">
    {{ $slot }}
</a>