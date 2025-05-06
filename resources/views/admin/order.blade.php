@php
$titles=[
'Id','Customer Name','Payment State','Pay Method','Created At','Amount'
]
@endphp
<html>

<head>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        // Chart 1: Area Chart
        google.charts.load('current', {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawAreaChart);

        function drawAreaChart() {
            const rawDataCustomer = @json($areaChartCustomer['data']);
            const rawDataSales = @json($areaChartSales['data']);

            // Create a map to merge both datasets by year
            const yearMap = {};

            rawDataCustomer.forEach(item => {
                const year = item.year.toString();
                yearMap[year] = {
                    year,
                    customer: item.total,
                    sales: 0
                };
            });

            rawDataSales.forEach(item => {
                const year = item.year.toString();
                if (!yearMap[year]) {
                    yearMap[year] = {
                        year,
                        customer: 0,
                        sales: item.total
                    };
                } else {
                    yearMap[year].sales = item.total;
                }
            });

            // Convert merged data into array format for chart
            const chartData = [
                ['Year', 'Customer', 'Sale']
            ];
            Object.values(yearMap).forEach(entry => {
                chartData.push([entry.year, entry.customer, entry.sales]);
            });

            const data = google.visualization.arrayToDataTable(chartData);

            const options = {
                title: 'Company Performance (Customers & Sales)',
                hAxis: {
                    title: 'Year',
                    titleTextStyle: {
                        color: '#333'
                    }
                },
                vAxis: {
                    minValue: 0
                },
                backgroundColor: '#f9fafb'
            };

            const chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>

<body class="bg-gray-100 font-sans">
    <x-admin-layout>
        <div class="flex flex-col gap-10">
            <div class=" gap-6 px-6 pb-10">
                <div class="bg-white shadow rounded-lg p-6">
                    <!-- <h2 class="text-lg font-semibold text-gray-700 mb-4">This chart shows the user over the years.</h2> -->

                    <div id="chart_div" class="w-full h-[450px]"></div>
                </div>

            </div>

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Order Products</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 w-10">
                                    <input type="checkbox" id="select-all-checkbox" class="form-checkbox text-blue-500">
                                </th>
                                @foreach ($titles as $title)
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $title }}
                                </th>
                                @endforeach
                                <th class="px-6 py-3 text-center">
                                    <!-- Trash Icon Header -->
                                    <button type="button" id="delete-selected-btn"><svg
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>

                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders['data'] as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="selected_products[]" value="{{ $order['id'] }}"
                                        class="product-checkbox text-blue-600">
                                </td>
                                <td class="px-6 py-4 text-gray-700"><a
                                        href="order/{{ $order['id']??null}}">{{ $order['id'] ?? null }}</a></td>
                                <td class="px-6 py-4 text-gray-700"><a
                                        href="order/{{ $order['id']??null}}">{{ $order['customer']['full_name'] ?? null }}</a>
                                </td>
                                <td class="px-6 py-4 text-gray-700"><a
                                        href="order/{{ $order['id'] ?? null }}">{{ $order['state'] ?? null }}</a></td>
                                <td class="px-6 py-4 text-gray-700"><a
                                        href="order/{{ $order['id'] ?? null }}">{{ $order['pay_method'] ?? null }}</a>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    <a
                                        href="order/{{ $order['id'] ?? null }}">{{ \Carbon\Carbon::parse($order['created_at'])->format('F j, Y H:i') }}</a>
                                </td>
                                <td class="px-6 py-4 text-green-500">
                                    <a
                                        href="{{ $order['id'] ?? null }}">${{ number_format($order['amount'] ?? 0, 2) }}</a>

                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="/admin/product" class="text-blue-600 hover:underline font-medium">Edit</a>
                                    <button class="text-red-600 hover:text-red-800 font-medium delete-btn"
                                        data-id="{{ $order['id'] }}">
                                        <span class="hidden sm:inline">Delete</span>
                                        <span class="sm:hidden">🗑️</span>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="p-4 border-t flex justify-center space-x-1 bg-white">
                        @foreach ($orders['links'] as $link)
                        @if ($link['url'])
                        <a href="{{ $link['url'] }}"
                            class="px-3 py-1 border rounded text-sm {{ $link['active'] ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                            {!! $link['label'] !!}
                        </a>
                        @else
                        <span class="px-3 py-1 border rounded text-sm text-gray-400 cursor-not-allowed">{!!
                            $link['label']
                            !!}</span>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
</body>

</html>
<script>
    // ✅ Handle Select All
    document.getElementById('select-all-checkbox').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = checked);
    });

    // ✅ Handle Bulk Delete
    document.getElementById('delete-selected-btn').addEventListener('click', function() {
        const selected = [...document.querySelectorAll('.product-checkbox:checked')].map(cb => cb.value);
        if (selected.length === 0) {
            Swal.fire('No Selection', 'Please select at least one product to delete.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selected.length} order.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then(result => {
            console.log(selected);

            if (result.isConfirmed) {
                fetch('/admin/many-order', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            ids: selected
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.message === "Orders deleted successfully") {
                            Swal.fire('Deleted!', `${data.deleted} products deleted.`, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', 'Failed to delete products.', 'error');
                        }
                    });
            }
        });
    });

    // ✅ Handle Single Delete
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This product will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                const orderId = this.dataset.id;
                if (result.isConfirmed) {
                    fetch(`/admin/order/${orderId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },

                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.message === "Order deleted successfully") {
                                Swal.fire('Deleted!', 'Order has been deleted.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'Failed to delete product.', 'error');
                            }
                        });


                }
            });
        });
    });
</script>