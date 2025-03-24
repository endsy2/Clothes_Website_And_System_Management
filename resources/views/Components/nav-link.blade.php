@props(['active' => false, 'href'])

<a class="{{ $active ? 'bg-black' : 'bg-amber-300' }}" href="{{ $href }}">
    {{ $slot }}
</a>