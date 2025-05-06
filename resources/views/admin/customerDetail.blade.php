<x-admin-layout>
    <div class="max-w-4xl mx-auto mt-12 space-y-10">
        <!-- Customer Profile Card -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
            <div class="bg-black px-8 py-6 text-white">
                <h1 class="text-3xl font-bold">Customer Details</h1>
                <p class="text-sm mt-1">Detailed customer profile overview</p>
            </div>

            <div class="p-8 space-y-8">
                <div class="flex flex-col items-center text-center gap-3">
                    <div
                        class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center text-3xl font-bold text-gray-600">
                        {{ strtoupper(substr($customers['full_name'], 0, 1)) }}
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $customers['full_name'] }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-gray-700">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="text-base font-semibold">{{ $customers['email'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Phone Number</p>
                        <p class="text-base font-semibold">{{ $customers['phone_number'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Location</p>
                        <p class="text-base font-semibold">{{ $customers['address'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Order History</h2>

            @if (!empty($customers['orders']))
            <div class="space-y-4">
                @foreach ($customers['orders'] as $order)
                <div class="border border-gray-100 rounded-xl p-4 hover:shadow-sm transition-all duration-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-700">Invoice ID: <span
                                    class="text-gray-600">{{ $order['id'] }}</span></p>
                            <p class="text-sm text-gray-500">Date:
                                {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total</p>
                            <p class="text-lg font-bold text-[#128B9E]">
                                {{ $order['amount'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center">No order history found for this customer.</p>
            @endif
        </div>
    </div>
</x-admin-layout>