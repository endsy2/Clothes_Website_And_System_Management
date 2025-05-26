@props(['title', 'value', 'dollar' => false])

<div
    class="w-60 h-40 bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-2xl shadow-md p-5 flex flex-col justify-between transition hover:shadow-lg hover:scale-[1.02] duration-200">
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-sm text-gray-500 uppercase tracking-wide">{{ $title }}</h1>
            <p class="text-3xl font-bold text-gray-800 mt-1">
                {{ $dollar ? '$' . number_format($value, 2) : $value }}
            </p>
        </div>
        <div class="bg-gray-100 p-2 rounded-full shadow-inner">
            {{ $slot }}
        </div>
    </div>
    <div class="h-1 w-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full"></div>
</div>