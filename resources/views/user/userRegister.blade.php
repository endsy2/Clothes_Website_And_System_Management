<x-layout>
    <section class="mt-10  p-10 max-w-xl mx-auto   ">
        <h1 class="text-center text-3xl font-semibold text-gray-800">Create Account</h1>

        <form action="" class="flex flex-col gap-5 mt-8">

            <input type="text" class="w-full border border-gray-300 rounded-md p-3   " placeholder="Full Name" required>

            <!-- Gender Section -->
            <div class="flex flex-col gap-2">
                <label class="text-gray-700 font-medium">Gender</label>
                <div class="flex gap-6">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="gender" value="male" class="accent-blue-600" required>
                        <span>Male</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="gender" value="female" class="accent-pink-500" required>
                        <span>Female</span>
                    </label>
                </div>
            </div>

            <input type="email" class="w-full border border-gray-300 rounded-md p-3  " placeholder="Email Address"
                required>

            <input type="password" class="w-full border border-gray-300 rounded-md p-3  " placeholder="Password"
                required>

            <input type="password" class="w-full border border-gray-300 rounded-md p-3  " placeholder="Confirm Password"
                required>

            <label class="inline-flex items-center gap-2 mt-4">
                <input type="checkbox">
                <span>
                    I agree to PEDRO's Terms & Conditions and Privacy Policy.</span>
            </label>
            <input type="submit" value="Create Account"
                class="bg-black text-white py-3 rounded-md mt-4 hover:bg-gray-900 transition cursor-pointer">
        </form>
    </section>
</x-layout>