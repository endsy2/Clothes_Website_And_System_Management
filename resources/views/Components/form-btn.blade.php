<button
    {{ $attributes->merge(['<input type="submit" value="Create Account"
                class="bg-black text-white py-3 rounded-md mt-4 hover:bg-gray-900 transition cursor-pointer" />' ,"type"=>'submit']) }}>
    {{ $slot }}
</button>