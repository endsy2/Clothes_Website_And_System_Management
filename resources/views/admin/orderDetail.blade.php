@php
$heads = ['Product', 'Quantity', 'Unit Price', 'Amount'];
@endphp

<x-admin-layout>
    <div class="max-w-5xl mx-auto bg-white p-10 rounded-2xl shadow-2xl border border-gray-200">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Invoice</h1>
            <div class="text-right text-sm text-gray-600 space-y-1">
                <p>Invoice ID: <span class="font-semibold text-black">{{ $orders['id'] }}</span></p>
                <p>Date: <span
                        class="font-semibold text-black">{{ \Carbon\Carbon::parse($orders['created_at'])->format('d M Y') }}</span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-10">
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Customer Info</h2>
                <ul class="text-gray-600 text-base space-y-1">
                    <li><strong>Name:</strong> {{ $orders['customer']['full_name'] }}</li>
                    <li><strong>Address:</strong> {{ $orders['customer']['address'] }}</li>
                    <li><strong>Phone:</strong> {{ $orders['customer']['phone_number'] }}</li>
                </ul>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Billing Info</h2>
                <ul class="text-gray-600 text-base space-y-1">
                    <li><strong>Payment:</strong> {{ $orders['payment_method'] ?? 'N/A' }}</li>
                    <li><strong>Status:</strong> <span
                            class="font-medium text-green-600">{{ $orders['status'] ?? 'Pending' }}</span></li>
                </ul>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-300">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        @foreach ($heads as $head)
                        <th class="px-6 py-3 text-left tracking-wider">{{ $head }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($orders['order_items'] as $item)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $item['product_variant']['product']['name'] }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $item['quantity'] }}</td>
                        <td class="px-6 py-4 text-start  text-gray-700">
                            ${{ number_format($item['product_variant']['price'], 2) }}</td>
                        <td class="px-6 py-4 text-start text-gray-700">
                            ${{ number_format($item['quantity'] * $item['product_variant']['price'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-right">
            <p class="text-2xl font-bold text-gray-900">
                Total: $
                {{ number_format(collect($orders['order_items'])->sum(fn($i) => $i['quantity'] * $i['product_variant']['price']), 2) }}
            </p>
        </div>

        <div class="mt-10 flex justify-end gap-4">
            <button onclick="window.print()"
                class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                Print Invoice
            </button>

        </div>
    </div>
</x-admin-layout>