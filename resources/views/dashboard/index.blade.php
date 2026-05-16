{{-- dashboard index blade --}}
<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">
                    Dashboard SWDKLLJ
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Monitoring pembayaran kendaraan Jasa Raharja
                </p>

            </div>

            <div class="flex items-center gap-3 bg-blue-600 text-white px-5 py-3 rounded-2xl shadow-sm">

                <i data-lucide="calendar-days" class="w-5 h-5"></i>

                <div>

                    <p class="text-xs text-blue-100">
                        Hari Ini
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

            <!-- IMPORT -->
            <div class="card-ui rounded-[28px] p-6">

                <div class="flex items-center gap-3 mb-5">

                    <div class="h-12 w-12 rounded-2xl bg-blue-100 flex items-center justify-center">

                        <i data-lucide="file-up" class="w-5 h-5 text-blue-600"></i>

                    </div>

                    <div>

                        <h3 class="font-bold text-lg text-slate-800">
                            Import Data
                        </h3>

                        <p class="text-sm text-slate-500">
                            Upload file Excel / CSV SWDKLLJ
                        </p>

                    </div>

                </div>

                @if(session('success'))
                    <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">

                        <ul class="space-y-1">

                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>
                @endif

                <form
                    action="{{ route('import.excel') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="grid lg:grid-cols-[1fr_auto] gap-4"
                >

                    @csrf

                    <input
                        type="file"
                        name="files[]"
                        multiple
                        class="w-full border border-slate-300 rounded-2xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl font-semibold transition flex items-center justify-center gap-2"
                    >
                        <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                        Import
                    </button>

                </form>

            </div>

            <!-- STATS -->
            <div class="grid grid-cols-2 xl:grid-cols-3 gap-4">

                <!-- TOTAL -->
                <div class="card-ui rounded-[28px] p-5">

                    <div class="flex justify-between items-start">

                        <div>

                            <p class="text-sm text-slate-500">
                                Total Data
                            </p>

                            <h3 class="text-3xl font-bold text-slate-800 mt-3">
                                {{ $totalData }}
                            </h3>

                        </div>

                        <div class="h-12 w-12 rounded-2xl bg-blue-100 flex items-center justify-center">

                            <i data-lucide="database" class="w-5 h-5 text-blue-600"></i>

                        </div>

                    </div>

                </div>

                <!-- LUNAS -->
                <div class="card-ui rounded-[28px] p-5">

                    <div class="flex justify-between items-start">

                        <div>

                            <p class="text-sm text-slate-500">
                                Sudah Bayar
                            </p>

                            <h3 class="text-3xl font-bold text-green-600 mt-3">
                                {{ $sudahBayar }}
                            </h3>

                        </div>

                        <div class="h-12 w-12 rounded-2xl bg-green-100 flex items-center justify-center">

                            <i data-lucide="badge-check" class="w-5 h-5 text-green-600"></i>

                        </div>

                    </div>

                </div>


                <!-- BELUM -->
                <div class="card-ui rounded-[28px] p-5">

                    <div class="flex justify-between items-start">

                        <div>

                            <p class="text-sm text-slate-500">
                                Belum Bayar
                            </p>

                            <h3 class="text-3xl font-bold text-red-500 mt-3">
                                {{ $belumBayar }}
                            </h3>

                        </div>

                        <div class="h-12 w-12 rounded-2xl bg-red-100 flex items-center justify-center">

                            <i data-lucide="circle-x" class="w-5 h-5 text-red-500"></i>

                        </div>

                    </div>

                </div>

            </div>
                    <!-- CHART + MONITORING -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

                <!-- CHART -->
                <div class="xl:col-span-2 card-ui rounded-[28px] p-5">

                    <div class="flex items-center justify-between mb-5">

                        <div>

                            <h3 class="font-bold text-lg text-slate-800">
                                Statistik SWDKLLJ
                            </h3>

                            <p class="text-sm text-slate-500">
                                Monitoring pembayaran kendaraan
                            </p>

                        </div>

                        <div class="h-11 w-11 rounded-2xl bg-blue-100 flex items-center justify-center">

                            <i data-lucide="bar-chart-3" class="w-5 h-5 text-blue-600"></i>

                        </div>

                    </div>

                    <div
                        id="chart"
                        class="min-h-[260px]"
                    ></div>

                </div>

                <!-- MONITORING -->
                <div class="card-ui rounded-[28px] p-5">

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h3 class="font-bold text-lg text-slate-800">
                                Status Monitoring
                            </h3>

                            <p class="text-sm text-slate-500">
                                Persentase pembayaran
                            </p>

                        </div>

                        <div class="h-11 w-11 rounded-2xl bg-cyan-100 flex items-center justify-center">

                            <i data-lucide="activity" class="w-5 h-5 text-cyan-600"></i>

                        </div>

                    </div>

                    <div class="space-y-6">

                        <!-- LUNAS -->
                        <div>

                            <div class="flex justify-between mb-2">

                                <div class="flex items-center gap-2">

                                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>

                                    <span class="text-sm font-medium text-slate-700">
                                        Sudah Bayar
                                    </span>

                                </div>

                                <span class="font-bold text-green-600 text-sm">
                                    {{ $sudahBayar }}
                                </span>

                            </div>

                            <div class="w-full h-2.5 rounded-full bg-slate-200 overflow-hidden">

                                <div
                                    class="bg-green-500 h-full rounded-full"
                                    style="width: {{ $totalData > 0 ? ($sudahBayar / $totalData) * 100 : 0 }}%"
                                ></div>

                            </div>

                        </div>


                        <!-- BELUM -->
                        <div>

                            <div class="flex justify-between mb-2">

                                <div class="flex items-center gap-2">

                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>

                                    <span class="text-sm font-medium text-slate-700">
                                        Belum Bayar
                                    </span>

                                </div>

                                <span class="font-bold text-red-500 text-sm">
                                    {{ $belumBayar }}
                                </span>

                            </div>

                            <div class="w-full h-2.5 rounded-full bg-slate-200 overflow-hidden">

                                <div
                                    class="bg-red-500 h-full rounded-full"
                                    style="width: {{ $totalData > 0 ? ($belumBayar / $totalData) * 100 : 0 }}%"
                                ></div>

                            </div>

                        </div>

                    </div>

                    <!-- SUMMARY -->
                    <div class="mt-6 pt-5 border-t border-slate-200">

                        <div class="grid grid-cols-2 gap-4">

                            <div class="bg-slate-50 rounded-2xl p-4">

                                <p class="text-xs text-slate-500">
                                    Total Kendaraan
                                </p>

                                <h4 class="font-bold text-xl text-slate-800 mt-1">
                                    {{ $totalData }}
                                </h4>

                            </div>

                            <div class="bg-slate-50 rounded-2xl p-4">

                                <p class="text-xs text-slate-500">
                                    Lunas
                                </p>

                                <h4 class="font-bold text-xl text-green-600 mt-1">
                                    {{ $sudahBayar }}
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
                        <!-- TABLE -->
            <div class="card-ui rounded-[28px] p-5">

                <!-- HEADER -->
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-5">

                    <div>

                        <h3 class="font-bold text-lg text-slate-800">
                            Data Monitoring SWDKLLJ
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Data kendaraan hasil import
                        </p>

                    </div>

                </div>

                <!-- SEARCH -->
                <form
                    method="GET"
                    action="{{ route('dashboard') }}"
                    class="mb-5"
                >

                    <div class="grid lg:grid-cols-[1fr_auto_auto] gap-3">

                        <!-- INPUT -->
                        <div class="relative">

                            <i
                                data-lucide="search"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                            ></i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nopol, nama WP, alamat, no hp..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            >

                        </div>

                        <!-- SEARCH BTN -->
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-medium transition flex items-center justify-center gap-2"
                        >
                            <i data-lucide="search" class="w-4 h-4"></i>
                            Search
                        </button>

                        <!-- RESET BTN -->
                        <a
                            href="{{ route('dashboard') }}"
                            class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 px-6 py-3 rounded-2xl font-medium transition flex items-center justify-center gap-2"
                        >
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            Reset
                        </a>

                    </div>

                </form>

                <!-- TABLE -->
                <div class="overflow-hidden rounded-[24px] border border-slate-200">

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1700px]">

                            <thead class="bg-slate-100">

                                <tr class="text-slate-600 text-xs uppercase">

                                    <th class="text-left py-4 px-4">ID</th>
                                    <th class="text-left py-4 px-4">RIC</th>
                                    <th class="text-left py-4 px-4">NOPOL</th>
                                    <th class="text-left py-4 px-4">NAMA WP</th>
                                    <th class="text-left py-4 px-4">ALAMAT</th>
                                    <th class="text-left py-4 px-4">MASA BERLAKU</th>
                                    <th class="text-left py-4 px-4">GOL</th>
                                    <th class="text-left py-4 px-4">NO HP</th>
                                    <th class="text-left py-4 px-4">PENYERAHAN</th>
                                    <th class="text-left py-4 px-4">KEPEMILIKAN</th>
                                    <th class="text-left py-4 px-4">STATUS</th>
                                    <th class="text-left py-4 px-4">METODE</th>
                                    <th class="text-left py-4 px-4">NOMINAL BAYAR</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse ($data as $item)

                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition">

                                        <td class="py-4 px-4 text-sm text-slate-700">
                                            {{ $item->id_petugas ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4 text-sm font-medium">
                                            {{ $item->nama_ric ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4">

                                            <span class="bg-slate-100 px-3 py-1 rounded-xl text-sm font-semibold text-slate-800">
                                                {{ $item->nopol ?? '-' }}
                                            </span>

                                        </td>

                                        <td class="py-4 px-4 text-sm">
                                            {{ $item->nama_wp ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4 text-sm max-w-[220px] whitespace-normal text-slate-600">
                                            {{ $item->alamat ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4 text-sm">
    {{ $item->masa_berlaku
        ? \Carbon\Carbon::parse($item->masa_berlaku)->format('d-m-Y')
        : '-'
    }}
</td>

                                        <td class="py-4 px-4 text-sm">
                                            {{ $item->gol ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4 text-sm">
                                            {{ $item->no_hp ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4 text-sm">
                                            {{ $item->status_penyerahan ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4 text-sm">
                                            {{ $item->status_kepemilikan ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4">

                                            @php
                                                $status = strtoupper($item->status_bayar ?? 'BELUM');
                                            @endphp

                                            @if($status == 'LUNAS')

                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                    LUNAS
                                                </span>

                                            @elseif($status == 'DP')

                                                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-semibold">
                                                    DP
                                                </span>

                                            @else

                                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">
                                                    BELUM
                                                </span>

                                            @endif

                                        </td>
                                        <!-- METODE PEMBAYARAN -->
<td class="py-4 px-4 text-center">

    @php
        $metode = strtolower($item->metode_pembayaran ?? '');
    @endphp

    @if($status !== 'LUNAS')

        <span class="text-slate-400 text-sm">
            -
        </span>

    @elseif($metode == 'online')

        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
            ONLINE
        </span>

    @elseif($metode == 'konvensional')

        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
            KONVENSIONAL
        </span>

    @else

        <span class="text-slate-400 text-sm">
            -
        </span>

    @endif

</td>

                                        <td class="py-4 px-4 text-sm font-semibold text-green-600 text-right">

    @if($item->nominal_bayar)

        Rp {{ number_format($item->nominal_bayar, 0, ',', '.') }}

    @else

        -

    @endif

</td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="13" class="text-center py-14">

                                            <div class="flex flex-col items-center">

                                                <i
                                                    data-lucide="database-x"
                                                    class="w-10 h-10 text-slate-300 mb-3"
                                                ></i>

                                                <h4 class="font-semibold text-slate-700">
                                                    Belum ada data
                                                </h4>

                                                <p class="text-sm text-slate-400">
                                                    Silakan import data terlebih dahulu
                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                <!-- PAGINATION -->
                <div class="mt-5">
                    {{ $data->links() }}
                </div>

            </div>

        </div>

    </div>

    <!-- APEXCHART -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>

        const options = {

            chart: {
                type: 'bar',
                height: 260,
                toolbar: {
                    show: false
                },
                fontFamily: 'Figtree'
            },

            series: [{
                name: 'Jumlah',
                data: [
                    {{ $sudahBayar }},
                    {{ $belumBayar }}
                ]
            }],

            xaxis: {
                categories: [
                    'LUNAS',
                    'BELUM'
                ]
            },

            dataLabels: {
                enabled: false
            },

            legend: {
                show: false
            },

            colors: ['#2563EB'],

            plotOptions: {
                bar: {
                    borderRadius: 10,
                    columnWidth: '45%'
                }
            }

        };

        const chart = new ApexCharts(
            document.querySelector("#chart"),
            options
        );

        chart.render();

    </script>

</x-app-layout>