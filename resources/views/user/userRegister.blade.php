<x-layout>
    <section class="mt-10 p-10 max-w-xl mx-auto">
        <h1 class="text-center text-3xl font-semibold text-gray-800">Create Account</h1>

        <form method="POST" action="/register" class="flex flex-col gap-4 mt-8">
            @csrf

            <x-form name="full_name" type="text" class="w-full border border-gray-300 rounded-md p-3"
                placeholder="Full Name" required />
            <x-error-form name="full_name" />

            <x-form name="email" type="email" class="w-full border border-gray-300 rounded-md p-3"
                placeholder="Email Address" required />
            <x-error-form name="email" />

            <x-form name="address" type="text" class="w-full border border-gray-300 rounded-md p-3"
                placeholder="Address" required />
            <x-error-form name="address" />


            <x-form name="password" type="password" class="w-full border border-gray-300 rounded-md p-3"
                placeholder="Password" required />
            <x-error-form name="password" />

            <x-form name="password_confirmation" type="password" class="w-full border border-gray-300 rounded-md p-3"
                placeholder="Confirm Password" required />
            <x-error-form name="password_confirmation" />

            <label class="inline-flex items-center gap-2 mt-4">
                <input type="checkbox" required>
                <span>I agree to Clothing's Terms & Conditions and Privacy Policy.</span>
            </label>

            <x-form-btn class="bg-black text-white py-3 rounded-md mt-4 hover:bg-gray-900 transition cursor-pointer">
                Create Account
            </x-form-btn>
        </form>
    </section>
</x-layout>