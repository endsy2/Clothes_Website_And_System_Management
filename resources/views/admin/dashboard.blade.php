@php
$titles=[
'Id','name','category','brand','price']
@endphp

<html>

<head>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        // Chart 1: Area Chart
        const AreaChart = @json($areaChart);

        google.charts.load('current', {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawAreaChart);

        function drawAreaChart() {
            const chartData = [
                ['Year', 'Income']
            ];

            AreaChart.forEach(item => {
                chartData.push([item.year.toString(), item.total]);
            });

            const data = google.visualization.arrayToDataTable(chartData);

            const options = {
                title: 'Company Performance (Income per Year)',
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

        // Chart 2: Bar Chart
        // google.charts.setOnLoadCallback(drawBarChart);

        // function drawBarChart() {
        //     const data = google.visualization.arrayToDataTable([
        //         ["Element", "Density", {
        //             role: "style"
        //         }],
        //         ["Copper", 8.94, "#b87333"],
        //         ["Silver", 10.49, "silver"],
        //         ["Gold", 19.30, "gold"],
        //         ["Platinum", 21.45, "color: #e5e4e2"]
        //     ]);

        //     const view = new google.visualization.DataView(data);
        //     view.setColumns([0, 1, {
        //         calc: "stringify",
        //         sourceColumn: 1,
        //         type: "string",
        //         role: "annotation"
        //     }, 2]);

        //     const options = {
        //         title: "Density of Precious Metals, in g/cm^3",
        //         width: '100%',
        //         height: 400,
        //         bar: {
        //             groupWidth: "95%"
        //         },
        //         legend: {
        //             position: "none"
        //         },
        //         backgroundColor: '#f9fafb'
        //     };

        //     const chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
        //     chart.draw(view, options);
        // }
    </script>
</head>

<body class="bg-gray-100 font-sans">
    <x-admin-layout>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 px-6">
            <x-admin-dashboard-cart :value="$countCustomer" title="Customer">
                <!-- User icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75a17.9 17.9 0 0 1-7.5-1.65Z" />
                </svg>
            </x-admin-dashboard-cart>

            <x-admin-dashboard-cart :value="$countOrder" title="Order">
                <!-- Order icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.4c.5 0 .95.3 1.1.8l.38 1.45M7.5 14.25a3 3 0 0 0-3 3h15.75M7.5 14.25H18.7c1.1-2.3 2.1-4.7 2.9-7.1a60.1 60.1 0 0 0-16.5-1.85M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </x-admin-dashboard-cart>

            <x-admin-dashboard-cart :value="$totalRevenue" dollar="Revenue" title="Revenue">
                <!-- Dollar icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m-3-2.82.88.66c1.17.88 3.07.88 4.24 0 1.17-.88 1.17-2.3 0-3.18-.72-.56-1.49-.78-2.26-.78-.73 0-1.45-.22-2-.66-1.1-.88-1.1-2.3 0-3.18s2.9-.88 4 .01l.42.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </x-admin-dashboard-cart>
        </div>

        <div class=" gap-6 px-6 pb-10">
            <div class="bg-white shadow rounded-lg p-6">
                <div id="chart_div" class="w-full h-[400px]"></div>
            </div>
            <!-- <div class="bg-white shadow rounded-lg p-6">
                <div id="columnchart_values" class="w-full h-[400px]"></div>
            </div> -->
        </div>
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Trending Products</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($titles as $title)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $title }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($trendProduct['products'] as $item)
                        @php
                        $productVariant = $item['product_variant'];
                        $product = $productVariant['product'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-700">{{ $product['id']??null }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $product['name']??null }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $product['category']['category_name'] ??null}}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $product['brand']['brand_name'] ??null}}</td>
                            <td class="px-6 py-4 text-gray-700">${{ number_format($productVariant['price']??null, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </x-admin-layout>
</body>

</html>