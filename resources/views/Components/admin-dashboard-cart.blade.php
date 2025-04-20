@props(['title', 'value', 'dollar' => false])

<div class="flex justify-between items-center w-56 h-40 bg-white border  rounded-lg shadow-sm p-4 mb-4">
    <div class="flex flex-col justify-between items-center mb-2">
        <h1 class="text-lg text-gray-800">{{ $title }}</h1>
        <p class="text-2xl font-semibold">
            {{ $dollar ? '$' . $value : $value }}
        </p>
    </div>
    <div class="flex justify-center items-center bg-gray-100 rounded-full shadow-md w-14 h-14">
        {{ $slot }}
    </div>
</div>