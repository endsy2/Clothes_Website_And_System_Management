<x-layout>
    <h1 class="text-center text-3xl mt-16 font-bold text-gray-800">Sign In / Create Account</h1>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 my-12 px-4 lg:px-20">

        <!-- Sign In Box -->
        <div
            class="flex flex-col justify-center items-center gap-6 border border-gray-300 rounded-2xl p-10 shadow-md bg-white">

            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-800">Sign In</h2>
                <p class="text-gray-600 mt-1">Sign in for a faster checkout experience</p>
            </div>

            <form action="" class="flex flex-col gap-5 w-full max-w-md">
                <input type="email" placeholder="Email Address*" class="py-3 px-4 border border-gray-300 rounded-md "
                    required>

                <input type="password" placeholder="Password*" class="py-3 px-4 border border-gray-300 rounded-md "
                    required>

                <input type="submit" value="Sign In"
                    class="bg-black text-white py-3 rounded-md font-medium hover:bg-gray-900 transition cursor-pointer">

                <a href="#" class="text-sm text-blue-500 hover:underline text-center">Forgot Password?</a>
            </form>
        </div>

        <!-- Create Account Box -->
        <div
            class="flex flex-col justify-center items-center border border-gray-300 rounded-2xl p-10 shadow-md bg-white gap-6">

            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-800">Create Account</h2>
                <p class="text-gray-600 mt-2 max-w-md">
                    Save your information to check out faster, save items to your wishlist, and view your purchase and
                    return history.
                </p>
            </div>

            <a href="/register"
                class="px-8 py-3 border border-black rounded-md font-medium text-black hover:bg-black hover:text-white transition">Create
                Account</a>
        </div>

    </section>
</x-layout>