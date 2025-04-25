@extends("components.admin-auth-layout")

@section("content")
<div class=" flex items-center justify-center bg-gradient-to-br  px-4 mt-52">
    <div class="bg-white shadow-2xl rounded-2xl w-full max-w-md p-10">
        <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-2">Welcome Admin</h2>
        <p class="text-center text-sm text-gray-500 mb-6">Please login to your account</p>

        <form action="/admin/login" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" placeholder="you@example.com" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-black transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" placeholder="********" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-black transition">
            </div>
            <x-error-form name="email" />
            <x-error-form name="password" />

            <div class="flex flex-col md:flex-row gap-3">
                <button type="submit"
                    class="w-full bg-black text-white py-2 rounded-lg font-semibold hover:bg-gray-900 transition duration-200">
                    Login
                </button>

                <a href="/admin/sigup"
                    class="w-full text-center bg-white border border-black text-black py-2 rounded-lg font-semibold hover:bg-black hover:text-white transition duration-200">
                    Register
                </a>
            </div>
        </form>
    </div>
</div>
@endsection