{{-- visualisasi index blade --}}
<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>
                <h2 class="text-3xl font-bold text-slate-800">
                    Visualisasi SWDKLLJ
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Monitoring pembayaran kendaraan Jasa Raharja
                </p>
            </div>

            <div class="flex items-center gap-3 bg-blue-600 text-white px-5 py-3 rounded-[24px] shadow-sm">

                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>

                <div>
                    <p class="text-xs text-blue-100">
                        Dashboard Analytics
                    </p>

                    <p class="font-semibold text-sm">
                        {{ now()->format('d M Y') }}
                    </p>
                </div>

            </div>

        </div>
    </x-slot>

    <div class="py-6">

        <div class="page-container space-y-6">

            <!-- STATS -->

<!-- STATS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    <!-- SUDAH BAYAR -->
    <div class="relative overflow-hidden rounded-[30px] bg-white border border-slate-200 p-6 shadow-sm">

        <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-green-50 blur-3xl"></div>

        <div class="relative z-10">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Sudah Bayar
                    </p>

                    <h3 class="text-4xl font-bold text-slate-800 mt-2">
                        {{ $totalsudah_bayar }}
                    </h3>

                    <p class="text-sm text-slate-500">
                        Kendaraan Sudah Bayar
                    </p>
                    <div class="mt-3 inline-flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-xl text-sm font-semibold">

    Rp {{ number_format($totalNominalsudah_bayar, 0, ',', '.') }}

</div>
                </div>

                <div class="h-16 w-16 rounded-[22px] bg-green-100 flex items-center justify-center">
                    <i data-lucide="badge-check" class="w-7 h-7 text-green-600"></i>
                </div>

            </div>

            <div class="mt-5 space-y-3">

                <div class="flex justify-between items-center bg-slate-50 px-4 py-3 rounded-2xl">

                    <div>
                        <p class="text-sm font-medium text-slate-700">
                            DP (Mobil)
                        </p>
                    </div>

                    <span class="font-bold text-blue-600">
                        {{ $dpCount }}
                    </span>

                </div>

                <div class="flex justify-between items-center bg-slate-50 px-4 py-3 rounded-2xl">

                    <div>
                        <p class="text-sm font-medium text-slate-700">
                            CI (Motor)
                        </p>
                    </div>

                    <span class="font-bold text-green-600">
                        {{ $ciCount }}
                    </span>

                </div>

            </div>

        </div>

    </div>

    <!-- BELUM BAYAR -->
    <div class="relative overflow-hidden rounded-[30px] bg-white border border-slate-200 p-6 shadow-sm">

        <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-red-50 blur-3xl"></div>

        <div class="relative z-10">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Belum Bayar
                    </p>

                    <h3 class="text-4xl font-bold text-slate-800 mt-2">
                        {{ $totalBelum }}
                    </h3>

                    <p class="text-sm text-slate-500">
                        Kendaraan Belum Bayar
                    </p>
                    <div class="mt-3 inline-flex items-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-xl text-sm font-semibold">

    Rp {{ number_format($totalNominalBelum, 0, ',', '.') }}

</div>
                </div>

                <div class="h-16 w-16 rounded-[22px] bg-red-100 flex items-center justify-center">
                    <i data-lucide="circle-x" class="w-7 h-7 text-red-600"></i>
                </div>

            </div>

            <div class="mt-5 space-y-3">

                <div class="flex justify-between items-center bg-slate-50 px-4 py-3 rounded-2xl">

                    <div>
                        <p class="text-sm font-medium text-slate-700">
                            DP (Mobil)
                        </p>
                    </div>

                    <span class="font-bold text-orange-600">
                        {{ $belumDP }}
                    </span>

                </div>

                <div class="flex justify-between items-center bg-slate-50 px-4 py-3 rounded-2xl">

                    <div>
                        <p class="text-sm font-medium text-slate-700">
                            CI (Motor)
                        </p>
                    </div>

                    <span class="font-bold text-red-600">
                        {{ $belumCI }}
                    </span>

                </div>

            </div>

        </div>

    </div>

    <!-- TOTAL PENDAPATAN -->
<div class="relative overflow-hidden rounded-[30px] bg-white border border-slate-200 p-6 shadow-sm">

    <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-violet-50 blur-3xl"></div>

    <div class="relative z-10 h-full flex flex-col justify-center">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-slate-500">
                    Gap Target Pendapatan
                </p>

                <h3 class="text-3xl font-bold text-slate-800 mt-3">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Pendapatan saat ini
                </p>

            </div>

            <div class="h-16 w-16 rounded-[22px] bg-violet-100 flex items-center justify-center shrink-0">

                <i data-lucide="wallet" class="w-7 h-7 text-violet-600"></i>

            </div>

        </div>

        <!-- POTENSI -->
        <div class="mt-6 bg-violet-50 border border-violet-100 rounded-2xl p-4 text-center">

            <p class="text-xs text-slate-500">
                Target (Rp) Potensi Kendaraan Tunggakan
            </p>

            <h4 class="text-xl font-bold text-violet-700 mt-1">
                Rp {{ number_format($totalSemuaBayar, 0, ',', '.') }}
            </h4>

        </div>

    </div>

</div>
</div>


            <!-- ANALYTICS -->
            <!-- ANALYTICS -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

                <!-- PIE CHART -->
                <div class="bg-white rounded-[32px] border border-slate-200 p-6 shadow-sm">

                    <div class="flex items-center justify-between mb-5">

                        <div>

                            <h3 class="text-xl font-bold text-slate-800">
                                Persentase Pembayaran
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Distribusi nominal pembayaran kendaraan
                            </p>

                        </div>

                        <div class="h-14 w-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                            <i data-lucide="pie-chart" class="w-6 h-6 text-blue-600"></i>

                        </div>

                    </div>

                    <div id="pieChart"></div>

                    <div class="grid grid-cols-2 gap-3 mt-5">

    <div class="bg-green-50 rounded-2xl p-4 border border-green-100">

        <p class="text-xs text-slate-500">
            Sudah Dibayar
        </p>

        <h4 class="font-bold text-green-600 mt-1">
            Rp {{ number_format($totalNominalsudah_bayar, 0, ',', '.') }}
        </h4>

    </div>

    <div class="bg-red-50 rounded-2xl p-4 border border-red-100">

        <p class="text-xs text-slate-500">
            Belum Dibayar
        </p>

        <h4 class="font-bold text-red-600 mt-1">
            Rp {{ number_format($totalNominalBelum, 0, ',', '.') }}
        </h4>

    </div>

</div>

                </div>

            


 

                <!-- PETUGAS -->
                <div class="bg-white rounded-[32px] border border-slate-200 p-6 shadow-sm">

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h3 class="text-xl font-bold text-slate-800">
                                Daftar Petugas
                            </h3>


                        </div>

                        <div class="h-14 w-14 rounded-2xl bg-indigo-100 flex items-center justify-center">

                            <i data-lucide="users" class="w-6 h-6 text-indigo-600"></i>

                        </div>

                    </div>

                    @php
                        $maxPetugas = $petugas->max('total');
                    @endphp

                    <div class="space-y-5 max-h-[420px] overflow-y-auto pr-2">

                        @foreach($petugas as $item)

<div>

    <div class="flex justify-between items-center mb-2">

        <div>

            <a
    href="{{ route('laporan.petugas', $item->nama_ric) }}"
    class="font-semibold text-slate-800 hover:text-blue-600"
>
    {{ $item->nama_ric }}
</a>

            <p class="text-xs text-slate-500">
                {{ $item->selesai }} selesai dari
                {{ $item->total }} data
            </p>

        </div>

        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-xl text-sm font-bold">
            {{ $item->progress }}%
        </span>

    </div>

    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">

        <div
            class="h-full rounded-full bg-green-500 transition-all duration-500"
            style="width: {{ $item->progress }}%"
        ></div>

    </div>

</div>

@endforeach
                    </div>

                </div>

            </div>


            <!-- STATUS KENDARAAN FULL -->
<div class="
    bg-white
    rounded-[32px]
    border
    border-slate-200
    p-6
    shadow-sm
">

    <div class="
        flex
        flex-col
        lg:flex-row
        lg:items-start
        lg:justify-between
        gap-6
    ">

        <!-- HEADER -->
        <div>

            <div class="
                flex
                items-center
                gap-4
            ">

                <div class="
                    h-16 w-16
                    rounded-[22px]
                    bg-cyan-100
                    flex
                    items-center
                    justify-center
                ">

                    <i
                        data-lucide="car"
                        class="w-7 h-7 text-cyan-600"
                    ></i>

                </div>

                <div>

                    <h3 class="
                        text-2xl
                        font-bold
                        text-slate-800
                    ">
                        Status Kepemilikan
                    </h3>

                    <p class="
                        text-sm
                        text-slate-500
                        mt-1
                    ">
                        Hasil sebaran dan
                        progres kerja seluruh
                        petugas RIC
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
<div class="
    grid
    grid-cols-1
    xl:grid-cols-2
    gap-6
    mt-8
    items-center
">

    <div class="
    flex
    justify-center
    items-center
    rounded-[30px]
    border
    border-slate-100
    bg-slate-50
    min-h-[520px]
">

        <div class="w-full max-w-[500px]">
            <div id="kepemilikanChart"></div>

            <div class="
    flex
    justify-center
    gap-3
    mt-3
">

    <div class="
        bg-slate-100
        rounded-2xl
        px-4
        py-3
        text-center
        min-w-[150px]
    ">

        <p class="
            text-xs
            text-slate-500
        ">
            Status Terbanyak
        </p>

        <h4 class="
            text-sm
            font-bold
            text-slate-800
            mt-1
        ">
            {{
                $statusDominan?->status
            }}
        </h4>

    </div>

    <div class="
        bg-slate-100
        rounded-2xl
        px-4
        py-3
        text-center
        min-w-[150px]
    ">

        <p class="
            text-xs
            text-slate-500
        ">
            Total Petugas
        </p>

        <h4 class="
            text-sm
            font-bold
            text-slate-800
            mt-1
        ">
            {{
                $totalPetugasAktif
            }}
            RIC
        </h4>

    </div>

</div>
        </div>

    </div>

    <!-- AKUMULASI -->
    <div class="
        rounded-[30px]
        bg-gradient-to-br
        from-cyan-600
        via-blue-600
        to-indigo-700
        p-6
        shadow-lg
        text-white
    ">

        <!-- HEADER -->
        <div class="
            flex
            items-start
            justify-between
        ">

            <div>

                <p class="
                    text-cyan-100
                    text-xs
                    uppercase
                    tracking-[0.2em]
                    font-semibold
                ">
                    Akumulasi Sebaran RIC
                </p>

                <div class="
                    flex
                    items-end
                    gap-3
                    mt-3
                ">

                    <h2 class="
                        text-5xl
                        font-black
                        leading-none
                    ">
                        {{
                            number_format(
                                $totalKerjaRic
                            )
                        }}
                    </h2>

                    <div class="pb-1">

                        <p class="
                            text-cyan-100
                            text-sm
                        ">
                            Total Kendaraan
                        </p>

                        <p class="
                            text-cyan-200
                            text-xs
                        ">
                            Sudah diproses petugas RIC
                        </p>

                    </div>

                </div>

            </div>

            <div class="
                w-14
                h-14
                rounded-[20px]
                bg-white/15
                backdrop-blur-sm
                flex
                items-center
                justify-center
            ">

                <i
                    data-lucide="activity"
                    class="w-6 h-6 text-white"
                ></i>

            </div>

        </div>

        <!-- STATS -->
        <div class="
            grid
            grid-cols-2
            gap-3
            mt-5
        ">

            <div class="
                bg-white/10
                border
                border-white/10
                rounded-2xl
                p-4
            ">

                <p class="
                    text-xs
                    text-cyan-100
                ">
                    Progress
                </p>

                <h4 class="
                    text-2xl
                    font-bold
                    mt-1
                ">
                    {{
                        $persenKerjaRic
                    }}%
                </h4>

            </div>

            <div class="
                bg-white/10
                border
                border-white/10
                rounded-2xl
                p-4
            ">

                <p class="
                    text-xs
                    text-cyan-100
                ">
                    Target
                </p>

                <h4 class="
                    text-2xl
                    font-bold
                    mt-1
                ">
                    {{
                        number_format(
                            $totalTargetRic
                        )
                    }}
                </h4>

            </div>

        </div>

        <!-- INSIGHT -->
<div class="
    grid
    grid-cols-3
    gap-3
    mt-4
">

    <!-- PETUGAS -->
    <div class="
        bg-white/10
        border
        border-white/10
        rounded-2xl
        p-4
        text-center
    ">

        <p class="
            text-xs
            text-cyan-100
        ">
            RIC Bertugas
        </p>

        <h4 class="
            text-xl
            font-bold
            mt-1
        ">
            {{
                $totalPetugasAktif
            }}
        </h4>

    </div>

    <!-- STATUS DOMINAN -->
    <div class="
        bg-white/10
        border
        border-white/10
        rounded-2xl
        p-4
        text-center
    ">

        <p class="
            text-xs
            text-cyan-100
        ">
            Terbanyak
        </p>

        <h4 class="
            text-sm
            font-bold
            mt-1
        ">
            {{
                $statusDominan?->status
            }}
        </h4>

    </div>

    <!-- SISA -->
    <div class="
        bg-white/10
        border
        border-white/10
        rounded-2xl
        p-4
        text-center
    ">

        <p class="
            text-xs
            text-cyan-100
        ">
            Belum Dikerjakan
        </p>

        <h4 class="
            text-xl
            font-bold
            mt-1
        ">
            {{
                number_format(
                    $sisaPengerjaan
                )
            }}
        </h4>

    </div>

</div>

        <!-- RINCIAN -->
        <div class="mt-5">

            <div class="
                flex
                justify-between
                mb-4
            ">

                <h4 class="
                    text-sm
                    font-semibold
                    text-cyan-100
                ">
                    Rincian Sebaran
                </h4>

                <span class="
                    text-xs
                    text-cyan-200
                ">
                    Total
                    {{
                        number_format(
                            $totalKerjaRic
                        )
                    }}
                </span>

            </div>

            <div class="
                space-y-2
                max-h-[260px]
                overflow-y-auto
                pr-1
            ">

                @php
    $statusColors = [

        'Dimiliki' =>
            '#0ea5e9',

        'Dijual' =>
            '#10b981',

        'Lainnya' =>
            '#f59e0b',

        'Ganti Alamat' =>
            '#8b5cf6',

        'Ganti Kepemilikan' =>
            '#ef4444',

        'Alamat tidak ditemukan' =>
            '#475569',

        'Rusak Berat' =>
            '#eab308',

        'Hilang' =>
            '#06b6d4',

    ];
@endphp

@foreach(
    $kepemilikanChart
    as $item
)

    @php
        $color =
            $statusColors[
                $item->status
            ] ?? '#94a3b8';
    @endphp

    <div class="
        flex
        justify-between
        items-center
        rounded-xl
        bg-white/10
        px-4
        py-3
        border
        border-white/10
    ">

        <div class="
            flex
            items-center
            gap-3
        ">

            <!-- DOT WARNA -->
            <span
                class="
                    w-3 h-3
                    rounded-full
                    shrink-0
                "
                style="
                    background:
                    {{ $color }};
                "
            ></span>

            <span class="
                text-sm
                text-cyan-50
                font-medium
            ">
                {{
                    $item->status
                }}
            </span>

        </div>

        <span class="
            font-bold
            text-white
        ">
            {{
                number_format(
                    $item->total
                )
            }}
        </span>

    </div>

@endforeach

            </div>

        </div>

    </div>

</div>

            <!-- SOURCE FILE -->
            <div class="bg-white rounded-[32px] border border-slate-200 p-6 shadow-sm">

                <div class="flex items-center justify-between mb-6">

                    <div>

                        <h3 class="text-xl font-bold text-slate-800">
                            Source Data Spreadsheet
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Data sinkronisasi Google Spreadsheet
                        </p>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-violet-100 flex items-center justify-center">

                        <i data-lucide="files" class="w-6 h-6 text-violet-600"></i>

                    </div>

                </div>

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

document.addEventListener('DOMContentLoaded', function () {

    const  totalSudahBayar = Number({{ $totalNominalsudah_bayar ?? 0 }});
    const totalBelum = Number({{ $totalNominalBelum ?? 0 }});

    const pieSeries = [
         totalSudahBayar,
        totalBelum
    ];

    const pieChart = new ApexCharts(
        document.querySelector("#pieChart"),
        {
            chart: {
                type: 'donut',
                height: 360,
                toolbar: {
                    show: false
                }
            },

            series: pieSeries,

            labels: [
                'Sudah Dibayar',
                'Belum Dibayar'
            ],

            colors: [
                '#16a34a',
                '#ef4444'
            ],

            legend: {
                position: 'bottom',
                fontSize: '14px',

                formatter: function (seriesName, opts) {

                    const series =
                        opts.w.globals.series;

                    const total =
                        series.reduce((a, b) => a + b, 0);

                    const current =
                        series[opts.seriesIndex] ?? 0;

                    const percent =
                        total > 0
                        ? ((current / total) * 100).toFixed(1)
                        : 0;

                    return `${seriesName} (${percent}%)`;
                }
            },

            tooltip: {
                enabled: true,

                y: {
                    formatter: function (value) {

                        return 'Rp ' +
                            new Intl.NumberFormat('id-ID')
                            .format(value);
                    }
                }
            },

            dataLabels: {
                enabled: false
            },

            stroke: {
                width: 0
            },

            plotOptions: {
                pie: {
                    expandOnClick: false,

                    donut: {
                        size: '72%',

                        labels: {
                            show: true,

                            name: {
                                show: true,
                                offsetY: -10
                            },

                            value: {
                                show: true,
                                fontSize: '34px',
                                fontWeight: 700,
                                offsetY: 12,

                                formatter: function (
                                    value,
                                    opts
                                ) {

                                    const series =
                                        opts.w.globals.series;

                                    const total =
                                        series.reduce(
                                            (a, b) => a + b,
                                            0
                                        );

                                    const current =
                                        series[
                                            opts.seriesIndex
                                        ] ?? 0;

                                    const percent =
                                        total > 0
                                        ? (
                                            (current / total) * 100
                                        ).toFixed(1)
                                        : 0;

                                    return percent + '%';
                                }
                            },

                            total: {
                                show: true,
                                label: 'Total Kendaraan',
                                fontSize: '18px',

                                formatter: function () {

                                    return '{{ $totalsudah_bayar + $totalBelum }}';
                                }
                            }
                        }
                    }
                }
            },

            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 320
                    }
                }
            }]
        }
    );

    pieChart.render();

    /*
    |--------------------------------------------------------------------------
    | SOURCE FILE CHART
    |--------------------------------------------------------------------------
    */

    const sourceChart = new ApexCharts(
        document.querySelector("#sourceChart"),
        {
            chart: {
                type: 'bar',
                height: 380,

                toolbar: {
                    show: false
                }
            },

            series: [{
                name: 'Jumlah Data',
                data: [
                    @foreach($sourceFiles as $item)
                        {{ $item->total ?? 0 }},
                    @endforeach
                ]
            }],

            xaxis: {
                categories: [
                    @foreach($sourceFiles as $item)
                        "{{ $item->source_file }}",
                    @endforeach
                ],

                labels: {
                    rotate: -20,
                    trim: true
                }
            },

            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 8,
                    distributed: true,
                    barHeight: '65%'
                }
            },

            colors: [
                '#8b5cf6',
                '#7c3aed',
                '#6d28d9',
                '#5b21b6',
                '#4c1d95',
                '#9333ea',
                '#a855f7',
                '#c084fc'
            ],

            dataLabels: {
                enabled: true
            },

            tooltip: {
                enabled: true
            },

            grid: {
                borderColor: '#e2e8f0'
            },

            legend: {
                show: false
            },

            noData: {
                text: 'Belum ada data'
            }
        }
    );

    sourceChart.render();

    /*
    |--------------------------------------------------------------------------
    | STATUS KENDARAAN
    |--------------------------------------------------------------------------
    */

    const kepemilikanChart = new ApexCharts(
        document.querySelector("#kepemilikanChart"),
        {
            chart: {
                type: 'donut',
                height: 360
            },

            series: [
                @foreach($kepemilikanChart as $item)
                    {{ $item->total }},
                @endforeach
            ],

            labels: [
                @foreach($kepemilikanChart as $item)
                    "{{ $item->status }}",
                @endforeach
            ],

            /*
            |--------------------------------------------------------------------------
            | WARNA DISESUAIKAN DENGAN KETERANGAN
            |--------------------------------------------------------------------------
            */

            colors: [
                '#0ea5e9', // Dimiliki
                '#10b981', // Dijual
                '#f59e0b', // Lainnya
                '#8b5cf6', // Ganti Kepemilikan
                '#ef4444', // Ganti Alamat
                '#475569', // Rusak Berat
                '#eab308', // Hilang
                '#06b6d4'  // Alamat tidak ditemukan
            ],

            legend: {
                show: false
            },

            dataLabels: {
                enabled: true,

                formatter: function (val) {
                    return val.toFixed(1) + '%';
                }
            },

            stroke: {
                width: 0
            },

            plotOptions: {
    pie: {

        expandOnClick: false,

        donut: {

            size: '72%',

            labels: {

                show: true,

                name: {
                    show: true,
                    offsetY: 38,
                    color: '#64748b',
                    fontSize: '14px',
                    fontWeight: 500
                },

                value: {
                    show: true,
                    fontSize: '42px',
                    fontWeight: 800,
                    color: '#0f172a',
                    offsetY: -10,

                    formatter: function () {

                        return '{{ number_format($totalKerjaRic) }}';
                    }
                },

                total: {
                    show: true,
                    label: 'Sudah Ditindaklanjuti',
                    color: '#64748b',
                    fontSize: '14px',
                    fontWeight: 500,

                    formatter: function () {

                        return '{{ $persenKerjaRic }}%';
                    }
                }
            }
        }
    }
},

            noData: {
                text: 'Belum ada data'
            }
        }
    );

    kepemilikanChart.render();

});
</script>

</x-app-layout>
