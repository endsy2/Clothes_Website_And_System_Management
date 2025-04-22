@extends('components.checkout-layout')

@section('content')
<div class="flex xl:px-5 2xl:px-80 ">
    <div class="border-r pr-10 w-1/2 h-full">
        @guest
        <div class="mt-10">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Contact</h2>
                <a href="/login" class="underline text-black">Login</a>
            </div>
            <input type="text" placeholder="Email or mobile phone number" class="block border p-2 mt-2 w-full">
        </div>

        <div class="mt-6">
            <h2 class="text-lg font-semibold">Delivery</h2>
            <div class="flex gap-4 mt-2">
                <input type="text" placeholder="Last name" class="border p-2 w-1/2">
                <input type="text" placeholder="First name" class="border p-2 w-1/2">
            </div>
            <input type="text" placeholder="Address" class="border p-2 w-full mt-2">
            <input type="text" placeholder="City" class="border p-2 w-full mt-2">
            <input type="number" placeholder="Phone" class="border p-2 w-full mt-2">
        </div>

        <div class="mt-6 p-4 bg-white border rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">💳 Payment Method</h2>
            <p class="text-sm text-gray-500 mb-4">All transactions are secure and encrypted.</p>

            <div class="space-y-3">
                <label
                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black transition duration-200">
                    <input type="radio" name="payment_method" class="accent-black mr-3">
                    <span class="text-gray-700 font-medium">ABA PAY</span>
                </label>

                <label
                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black transition duration-200">
                    <input type="radio" name="payment_method" class="accent-black mr-3">
                    <span class="text-gray-700 font-medium">By Delivery</span>
                </label>
            </div>
        </div>
        <button class="bg-black w-full text-white py-3 mt-5 rounded-md hover:bg-gray-700">Pay Now</button>


        @endguest
    </div>
    <div class="bg-gray-700 w-1/2 h-full pl-10">

    </div>
</div>
<!-- @guest
<div class="text-center mt-8">
    <p class="text-gray-700">Please <a href="/login" class="text-blue-600 underline">log in</a> to continue to checkout.
    </p>
</div>
@endguest -->
@endsection