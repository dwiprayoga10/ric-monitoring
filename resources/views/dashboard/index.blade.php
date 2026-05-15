<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>
                <h2 class="font-bold text-3xl text-slate-800">
                    Dashboard SWDKLLJ
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Monitoring data Jasa Raharja
                </p>
            </div>

            <div class="bg-white shadow rounded-2xl px-5 py-3 border border-slate-100">
                <p class="text-sm text-slate-500">
                    Hari Ini
                </p>

                <p class="font-bold text-slate-800">
                    {{ now()->format('d M Y') }}
                </p>
            </div>

        </div>
    </x-slot>

    <div class="py-8">

        <div class="max-w-[98%] mx-auto space-y-8">

            <!-- IMPORT -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                <div class="mb-6">

                    <h3 class="text-2xl font-bold text-slate-800">
                        Import Data Excel / CSV
                    </h3>

                    <p class="text-slate-500 text-sm mt-1">
                        Upload banyak file SWDKLLJ
                    </p>

                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-4">

                        <ul class="list-disc pl-5 space-y-1">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>
                @endif

                <form
                    action="{{ route('import.excel') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="flex flex-col md:flex-row gap-4"
                >

                    @csrf

                    <input
                        type="file"
                        name="files[]"
                        multiple
                        class="w-full border border-slate-300 rounded-2xl p-3"
                    >

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl transition font-semibold"
                    >
                        Import
                    </button>

                </form>

            </div>

            <!-- CARD -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                    <div class="flex justify-between items-start">

                        <div>

                            <h3 class="text-slate-500 text-sm">
                                Total Data
                            </h3>

                            <p class="text-4xl font-bold mt-4 text-slate-800">
                                {{ $totalData }}
                            </p>

                        </div>

                        <div class="bg-blue-100 text-blue-600 p-4 rounded-2xl text-2xl">
                            📊
                        </div>

                    </div>

                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                    <div class="flex justify-between items-start">

                        <div>

                            <h3 class="text-slate-500 text-sm">
                                Sudah Bayar
                            </h3>

                            <p class="text-4xl font-bold mt-4 text-green-600">
                                {{ $sudahBayar }}
                            </p>

                        </div>

                        <div class="bg-green-100 text-green-600 p-4 rounded-2xl text-2xl">
                            💰
                        </div>

                    </div>

                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                    <div class="flex justify-between items-start">

                        <div>

                            <h3 class="text-slate-500 text-sm">
                                DP
                            </h3>

                            <p class="text-4xl font-bold mt-4 text-orange-500">
                                {{ $dp }}
                            </p>

                        </div>

                        <div class="bg-orange-100 text-orange-500 p-4 rounded-2xl text-2xl">
                            🚗
                        </div>

                    </div>

                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                    <div class="flex justify-between items-start">

                        <div>

                            <h3 class="text-slate-500 text-sm">
                                Belum Bayar
                            </h3>

                            <p class="text-4xl font-bold mt-4 text-red-500">
                                {{ $belumBayar }}
                            </p>

                        </div>

                        <div class="bg-red-100 text-red-500 p-4 rounded-2xl text-2xl">
                            🎯
                        </div>

                    </div>

                </div>

            </div>

            <!-- CHART -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                    <div class="mb-6">

                        <h3 class="text-2xl font-bold text-slate-800">
                            Statistik SWDKLLJ
                        </h3>

                        <p class="text-slate-500 text-sm mt-1">
                            Monitoring pencapaian pembayaran
                        </p>

                    </div>

                    <div id="chart"></div>

                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                    <h3 class="text-2xl font-bold text-slate-800">
                        Status Monitoring
                    </h3>

                    <div class="mt-8 space-y-8">

                        <div>

                            <div class="flex justify-between mb-3">

                                <span class="text-slate-600">
                                    Sudah Bayar
                                </span>

                                <span class="font-bold text-green-600">
                                    {{ $sudahBayar }}
                                </span>

                            </div>

                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">

                                <div
                                    class="bg-green-500 h-3 rounded-full"
                                    style="width: {{ $totalData > 0 ? ($sudahBayar / $totalData) * 100 : 0 }}%"
                                ></div>

                            </div>

                        </div>

                        <div>

                            <div class="flex justify-between mb-3">

                                <span class="text-slate-600">
                                    DP
                                </span>

                                <span class="font-bold text-orange-500">
                                    {{ $dp }}
                                </span>

                            </div>

                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">

                                <div
                                    class="bg-orange-500 h-3 rounded-full"
                                    style="width: {{ $totalData > 0 ? ($dp / $totalData) * 100 : 0 }}%"
                                ></div>

                            </div>

                        </div>

                        <div>

                            <div class="flex justify-between mb-3">

                                <span class="text-slate-600">
                                    Belum Bayar
                                </span>

                                <span class="font-bold text-red-500">
                                    {{ $belumBayar }}
                                </span>

                            </div>

                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">

                                <div
                                    class="bg-red-500 h-3 rounded-full"
                                    style="width: {{ $totalData > 0 ? ($belumBayar / $totalData) * 100 : 0 }}%"
                                ></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                <!-- SEARCH -->
                <form method="GET" action="{{ route('dashboard') }}" class="mb-6">

                    <div class="flex flex-col md:flex-row gap-4">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nopol, nama wp, alamat, no hp..."
                            class="w-full border border-slate-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-semibold transition"
                        >
                            Search
                        </button>

                        <a
                            href="{{ route('dashboard') }}"
                            class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-3 rounded-2xl font-semibold transition text-center"
                        >
                            Reset
                        </a>

                    </div>

                </form>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

                    <div>

                        <h3 class="text-2xl font-bold text-slate-800">
                            Data Monitoring SWDKLLJ
                        </h3>

                        <p class="text-slate-500 text-sm mt-1">
                            Data kendaraan hasil import CSV
                        </p>

                    </div>

                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">

                    <table class="w-full min-w-[2100px]">

                        <thead class="bg-slate-100">

                            <tr class="text-slate-600 text-sm uppercase">

                                <th class="text-left py-4 px-4">ID PETUGAS</th>
                                <th class="text-left py-4 px-4">NAMA RIC</th>
                                <th class="text-left py-4 px-4">NOPOL</th>
                                <th class="text-left py-4 px-4">NAMA WP</th>
                                <th class="text-left py-4 px-4">ALAMAT</th>
                                <th class="text-left py-4 px-4">MASA BERLAKU</th>
                                <th class="text-left py-4 px-4">GOL</th>
                                <th class="text-left py-4 px-4">NO HP</th>
                                <th class="text-left py-4 px-4">STATUS PENYERAHAN</th>
                                <th class="text-left py-4 px-4">STATUS KEPEMILIKAN</th>
                                <th class="text-left py-4 px-4">STATUS BAYAR</th>
                                <th class="text-left py-4 px-4">SOURCE FILE</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($data as $item)

                                <tr class="border-b hover:bg-slate-50 transition">

                                    <td class="py-4 px-4">
                                        {{ $item->id_petugas ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4 font-medium">
                                        {{ $item->nama_ric ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4 font-semibold text-slate-800">
                                        {{ $item->nopol ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4">
                                        {{ $item->nama_wp ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4 max-w-[250px] whitespace-normal">
                                        {{ $item->alamat ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4">
                                        {{ $item->masa_berlaku ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4">
                                        {{ $item->gol ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4">
                                        {{ $item->no_hp ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4">
                                        {{ $item->status_penyerahan ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4">
                                        {{ $item->status_kepemilikan ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4">

                                        @php
                                            $status = strtoupper($item->status_bayar ?? 'BELUM');
                                        @endphp

                                        @if($status == 'LUNAS')

                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                                LUNAS
                                            </span>

                                        @elseif($status == 'DP')

                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
                                                DP
                                            </span>

                                        @else

                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                                                BELUM
                                            </span>

                                        @endif

                                    </td>

                                    <td class="py-4 px-4 text-sm text-slate-500">
                                        {{ $item->source_file ?? '-' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="12" class="text-center py-12 text-slate-400">
                                        Belum ada data SWDKLLJ
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <!-- PAGINATION -->
                <div class="mt-6">
                    {{ $data->links() }}
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>

        const options = {

            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },

            series: [{
                name: 'Jumlah',
                data: [
                    {{ $sudahBayar }},
                    {{ $dp }},
                    {{ $belumBayar }}
                ]
            }],

            xaxis: {
                categories: [
                    'LUNAS',
                    'DP',
                    'BELUM BAYAR'
                ]
            },

            dataLabels: {
                enabled: false
            },

            colors: ['#2563eb'],

            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '50%'
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