<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-800">
            Visualisasi Data SWDKLLJ
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-[95%] mx-auto space-y-8">

            <!-- CARD -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white rounded-3xl p-6 shadow border">
                    <h3 class="text-slate-500 text-sm">
                        LUNAS
                    </h3>

                    <p class="text-4xl font-bold text-green-600 mt-4">
                        {{ $lunas }}
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow border">
                    <h3 class="text-slate-500 text-sm">
                        DP
                    </h3>

                    <p class="text-4xl font-bold text-yellow-500 mt-4">
                        {{ $dp }}
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow border">
                    <h3 class="text-slate-500 text-sm">
                        BELUM BAYAR
                    </h3>

                    <p class="text-4xl font-bold text-red-500 mt-4">
                        {{ $belum }}
                    </p>
                </div>

            </div>

            <!-- CHART -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <!-- PIE -->
                <div class="bg-white rounded-3xl p-6 shadow border">

                    <h3 class="text-2xl font-bold mb-6">
                        Pie Status Pembayaran
                    </h3>

                    <div id="pieChart"></div>

                </div>

                <!-- BAR -->
                <div class="bg-white rounded-3xl p-6 shadow border">

                    <h3 class="text-2xl font-bold mb-6">
                        Top Petugas
                    </h3>

                    <div id="petugasChart"></div>

                </div>

            </div>

            <!-- SOURCE FILE -->
            <div class="bg-white rounded-3xl p-6 shadow border">

                <h3 class="text-2xl font-bold mb-6">
                    Source File Import
                </h3>

                <div id="sourceChart"></div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>

        /*
        |--------------------------------------------------------------------------
        | PIE CHART
        |--------------------------------------------------------------------------
        */

        const pieChart = new ApexCharts(document.querySelector("#pieChart"), {

            chart: {
                type: 'pie',
                height: 350
            },

            series: [
                {{ $lunas }},
                {{ $dp }},
                {{ $belum }}
            ],

            labels: [
                'LUNAS',
                'DP',
                'BELUM'
            ],

            colors: [
                '#22c55e',
                '#f59e0b',
                '#ef4444'
            ]

        });

        pieChart.render();

        /*
        |--------------------------------------------------------------------------
        | PETUGAS CHART
        |--------------------------------------------------------------------------
        */

        const petugasChart = new ApexCharts(document.querySelector("#petugasChart"), {

            chart: {
                type: 'bar',
                height: 350
            },

            series: [{
                name: 'Total',
                data: [
                    @foreach($petugas as $item)
                        {{ $item->total }},
                    @endforeach
                ]
            }],

            xaxis: {
                categories: [
                    @foreach($petugas as $item)
                        "{{ $item->nama_ric }}",
                    @endforeach
                ]
            },

            colors: ['#2563eb']

        });

        petugasChart.render();

        /*
        |--------------------------------------------------------------------------
        | SOURCE FILE
        |--------------------------------------------------------------------------
        */

        const sourceChart = new ApexCharts(document.querySelector("#sourceChart"), {

            chart: {
                type: 'bar',
                height: 350
            },

            series: [{
                name: 'Total',
                data: [
                    @foreach($sourceFiles as $item)
                        {{ $item->total }},
                    @endforeach
                ]
            }],

            xaxis: {
                categories: [
                    @foreach($sourceFiles as $item)
                        "{{ $item->source_file }}",
                    @endforeach
                ]
            },

            colors: ['#7c3aed']

        });

        sourceChart.render();

    </script>

</x-app-layout>