<x-admin-layout>
    <h1 class="text-2xl font-bold mb-2">Hello Admin</h1>
    <h1 class="text-sm font-light mb-6">Welcome Back !</h1>
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <p>This is your admin dashboard content.</p>
    </div>
    @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">

                        <h1>{{ $chart1->options['chart_title'] }}</h1>

                        @if (!empty($chart1->datasets) && isset($chart1->datasets[0]['data']))
                        {!! $chart1->renderHtml() !!}
                        @else
                        <p>No chart data available 🫠</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('javascript')
    @if (!empty($chart1->datasets) && isset($chart1->datasets[0]['data']))
    {!! $chart1->renderChartJsLibrary() !!}
    {!! $chart1->renderJs() !!}
    @endif
    @endsection

</x-admin-layout>