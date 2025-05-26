@props(['name'])
@error($name)
<p class="text-sm text-black font-semibold mt-1">{{ $message }}</p>
@enderror