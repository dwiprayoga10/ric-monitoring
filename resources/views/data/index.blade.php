
<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">
                    Data SWDKLLJ
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Monitoring data pembayaran kendaraan
                </p>

            </div>

            <div class="flex items-center gap-3 bg-slate-100 border border-slate-200 px-5 py-3 rounded-2xl">

                <i data-lucide="database" class="w-5 h-5 text-blue-600"></i>

                <div>

                    <p class="text-xs text-slate-500">
                        Total Data
                    </p>

                    <p class="font-semibold text-slate-800">
                        {{ $data->total() }}
                    </p>

                </div>

            </div>

        </div>

    </x-slot>

    <div class="py-6">

        <div class="page-container space-y-5">

            <!-- FILTER -->
            <div class="card-ui rounded-[28px] p-5">

                <form
                    method="GET"
                    action="{{ route('data.swdkllj') }}"
                >

                    <div class="grid lg:grid-cols-[1fr_260px_320px] gap-3">

                        <!-- SEARCH -->
                        <div class="relative">

                            <i
                                data-lucide="search"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                            ></i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nopol, nama WP, nomor HP..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            >

                        </div>

                        <!-- STATUS -->
                        <select
                            name="status"
                            class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            <option
                                value="LUNAS"
                                {{ request('status') == 'LUNAS' ? 'selected' : '' }}
                            >
                                LUNAS
                            </option>

                            <option
                                value="BELUM"
                                {{ request('status') == 'BELUM' ? 'selected' : '' }}
                            >
                                BELUM
                            </option>

                        </select>

                        

                        

                        <!-- BUTTON -->
                        <!-- BUTTON WRAPPER -->
<div class="grid grid-cols-2 gap-3 w-full">

    <!-- SEARCH -->
    <!-- SEARCH -->
<button
    type="submit"
    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-7 py-3 rounded-2xl font-medium transition flex items-center justify-center gap-2"
>
    <i data-lucide="search" class="w-4 h-4"></i>
    Search
</button>

<!-- RESET -->
<a
    href="{{ route('data.swdkllj') }}"
    class="w-full bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 px-7 py-3 rounded-2xl font-medium transition flex items-center justify-center gap-2"
>
    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
    Reset
</a>

</div>

                    </div>

                </form>

            </div>

            @if($statusDipilih === 'LUNAS')

<div class="card-ui rounded-[28px] p-5">

    <div class="mb-5">

        <h3 class="font-bold text-lg text-slate-800">
            Persentase Metode Pembayaran
        </h3>

        <p class="text-sm text-slate-500">
            Pembayaran lunas berdasarkan metode
        </p>

    </div>

    <div class="grid lg:grid-cols-2 gap-6 items-center">

        <!-- CHART -->
        <div id="metodeChart"></div>

        <!-- SUMMARY -->
        <div class="space-y-4">

            <div class="bg-blue-50 rounded-2xl p-5">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm text-slate-500">
                            ONLINE
                        </p>

                        <h3 class="font-bold text-2xl text-blue-700">
    Rp {{ number_format($nominalOnline, 0, ',', '.') }}
</h3>

<p class="text-sm text-slate-500 mt-1">
    {{ $online }} transaksi
</p>

                    </div>

                    <span class="text-xl font-bold text-blue-700">
                        {{ $persenOnline }}%
                    </span>

                </div>

            </div>

            <div class="bg-purple-50 rounded-2xl p-5">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm text-slate-500">
                            KONVENSIONAL
                        </p>

                        <h3 class="font-bold text-2xl text-purple-700">
    Rp {{ number_format($nominalKonvensional, 0, ',', '.') }}
</h3>

<p class="text-sm text-slate-500 mt-1">
    {{ $konvensional }} transaksi
</p>

                    </div>

                    <span class="text-xl font-bold text-purple-700">
                        {{ $persenKonvensional }}%
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endif

@if($statusDipilih === 'LUNAS')

<div class="card-ui rounded-[28px] p-5">

    <div class="mb-5">

        <h3 class="font-bold text-lg text-slate-800">
            Mapping Transaksi Online per KMP
        </h3>

        <p class="text-sm text-slate-500">
            Distribusi transaksi online berdasarkan
            kelurahan KMP resmi
        </p>

    </div>

    <!-- TOP 3 GLOBAL -->
<div class="mb-6">

    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">
        Top 3 KMP Transaksi Terbanyak
    </p>

    <div class="grid lg:grid-cols-3 gap-4">

        @foreach($topKmp as $index => $top)

        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold">
                    {{ $index + 1 }}
                </div>

                <div>
                    <p class="font-semibold text-slate-800 text-sm">
                        KMP {{ $top['nama'] }}
                    </p>

                    <p class="text-xs text-slate-500">
                        {{ $top['total'] }} transaksi online
                    </p>
                </div>

            </div>

            <span class="inline-flex items-center justify-center min-w-[44px] h-[44px] rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                {{ $top['total'] }}
            </span>

        </div>

        @endforeach

    </div>

</div>

    <div class="grid lg:grid-cols-3 gap-5">

    @foreach($kmpResults as $zona => $items)

    <div class="bg-slate-50 rounded-[26px] p-5">

        <div class="mb-5">

            <h4 class="font-bold text-slate-800">
                {{ $zona }}
            </h4>

            <p class="text-xs text-slate-500 mt-1">
                Klik KMP untuk melihat detail
            </p>

        </div>

        <div class="space-y-4">

            @foreach($items as $item)

            <details
                class="group border border-slate-200 rounded-[22px] bg-white overflow-hidden"
            >

                <summary
                    class="list-none cursor-pointer p-4 flex items-center justify-between hover:bg-slate-50 transition"
                >

                    <div>

                        <h4 class="font-semibold text-slate-800">
                            KMP {{ $item['nama'] }}
                        </h4>

                        <p class="text-xs text-slate-500 mt-1">
                            {{ $item['total'] }} transaksi online
                        </p>

                    </div>

                    <div class="flex items-center gap-3">

                        <span class="inline-flex items-center justify-center min-w-[42px] h-[42px] rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                            {{ $item['total'] }}
                        </span>

                        <i
                            data-lucide="chevron-down"
                            class="w-5 h-5 text-slate-400 transition-transform group-open:rotate-180"
                        ></i>

                    </div>

                </summary>

                @if($item['total'] > 0)

                <div class="p-4 border-t border-slate-100 bg-slate-50">

                    <div class="space-y-3">

                        @foreach($item['list'] as $trx)

                        <div class="bg-white rounded-2xl border border-slate-200 p-4">


    <div class="flex justify-between gap-4">

        <div class="flex-1">

            <!-- HEADER BADGE -->
            <div class="flex flex-wrap gap-2 mb-3">

                <span class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-1 text-sm font-bold text-slate-800">
                    {{ $trx->nopol ?? '-' }}
                </span>

                <span class="inline-flex items-center rounded-xl bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                    ID:
                    {{ $trx->id_petugas ?? '-' }}
                </span>

            </div>

            <!-- NAMA -->
            <h5 class="font-semibold text-slate-800">
                {{ $trx->nama_wp ?? '-' }}
            </h5>

            <!-- RIC -->
            <p class="text-sm text-slate-500 mt-1">
                RIC:
                <span class="font-medium text-slate-700">
                    {{ $trx->nama_ric ?? '-' }}
                </span>
            </p>

            <!-- ALAMAT -->
            <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                {{ $trx->alamat ?? '-' }}
            </p>

        </div>

        <!-- NOMINAL -->
        <div class="text-right min-w-[120px]">

            <p class="text-xs text-slate-400">
                Nominal
            </p>

            <p class="font-bold text-blue-700 mt-1">
                Rp {{ number_format($trx->nominal_bayar ?? 0, 0, ',', '.') }}
            </p>

        </div>

    </div>

</div>

                        @endforeach

                    </div>

                </div>

                @else

                <div class="p-4 text-sm text-slate-400">
                    Tidak ada transaksi online
                </div>

                @endif

            </details>

            @endforeach

        </div>

    </div>

    @endforeach

</div>
@endif
    

            <!-- TABLE -->
            <div class="card-ui rounded-[28px] p-5">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">

    <div>

        <h3 class="font-semibold text-slate-800">
            Daftar Data SWDKLLJ
        </h3>

        <p class="text-sm text-slate-500 mt-1">
            Menampilkan {{ $data->count() }} data dari
            {{ $data->total() }} total data
        </p>

    </div>

    <!-- FILTER ZONA -->
    <form method="GET" action="{{ route('data.swdkllj') }}">

        <!-- supaya search & status tidak hilang -->
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">

        <select
            name="zona"
            onchange="this.form.submit()"
            class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[220px]"
        >

            <option value="">
                Semua Zona
            </option>

            <option value="SAMSAT I"
                {{ request('zona') == 'SAMSAT I' ? 'selected' : '' }}>
                Samsat I
            </option>

            <option value="SAMSAT II"
                {{ request('zona') == 'SAMSAT II' ? 'selected' : '' }}>
                Samsat II
            </option>

            <option value="SAMSAT III"
                {{ request('zona') == 'SAMSAT III' ? 'selected' : '' }}>
                Samsat III
            </option>

        </select>

    </form>

</div>

                <!-- TABLE WRAPPER -->
                <div class="overflow-hidden rounded-[24px] border border-slate-200">

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1900px]">

                            <thead class="bg-slate-100 border-b border-slate-200">

                                <tr class="text-slate-600 text-xs uppercase tracking-wide">

                                    <th class="text-left py-4 px-4 font-semibold">
                                        ID PETUGAS
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        NAMA RIC
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        NOPOL
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        NAMA WP
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        ALAMAT
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        MASA BERLAKU
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        GOL
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        NO HP
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        PENYERAHAN
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        KEPEMILIKAN
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        STATUS
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        METODE PEMBAYARAN
                                    </th>

                                    <th class="text-left py-4 px-4 font-semibold">
                                        NOMINAL BAYAR
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="bg-white">

                                @forelse ($data as $item)

                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-200">

                                        <td class="py-4 px-4 text-sm">
                                            {{ $item->id_petugas ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4 text-sm">
                                            {{ $item->nama_ric ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4">

                                            <span class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-800">
                                                {{ $item->nopol ?? '-' }}
                                            </span>

                                        </td>

                                        <td class="py-4 px-4 text-sm">
                                            {{ $item->nama_wp ?? '-' }}
                                        </td>

                                        <td class="py-4 px-4 text-sm max-w-[250px] whitespace-normal">
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

                                                <span class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-3 py-2 rounded-full text-xs font-semibold">
                                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                    LUNAS
                                                </span>

                                            @elseif($status == 'DP')

                                                <span class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 px-3 py-2 rounded-full text-xs font-semibold">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                    DP
                                                </span>

                                            @else

                                                <span class="inline-flex items-center gap-2 bg-red-100 text-red-700 px-3 py-2 rounded-full text-xs font-semibold">
                                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                    BELUM
                                                </span>

                                            @endif

                                        </td>

                                        <td class="py-4 px-4 text-center">

                                            @php
                                                $metode = strtolower($item->metode_pembayaran ?? '');
                                            @endphp

                                            @if($status !== 'LUNAS')

                                                <span class="text-slate-400 text-sm">
                                                    -
                                                </span>

                                            @elseif($metode == 'online')

                                                <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-3 py-2 rounded-full text-xs font-semibold">
                                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                                    ONLINE
                                                </span>

                                            @elseif($metode == 'konvensional')

                                                <span class="inline-flex items-center gap-2 bg-purple-100 text-purple-700 px-3 py-2 rounded-full text-xs font-semibold">
                                                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                                    KONVENSIONAL
                                                </span>

                                            @else

                                                <span class="text-slate-400 text-sm">
                                                    -
                                                </span>

                                            @endif

                                        </td>

                                        <td class="py-4 px-4 text-sm font-semibold text-green-600">
                                            @if($item->nominal_bayar)
                                                Rp {{ number_format($item->nominal_bayar, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="13" class="py-16 text-center">
                                            Tidak ada data
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

    
@if($statusDipilih === 'LUNAS')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | CHART ONLINE VS KONVENSIONAL
    |--------------------------------------------------------------------------
    */

    const metodeChart =
        document.querySelector("#metodeChart");

    if (metodeChart) {

        const metodeOptions = {

            chart: {
                type: 'donut',
                height: 300
            },

            series: [
                {{ $online }},
                {{ $konvensional }}
            ],

            labels: [
                'ONLINE',
                'KONVENSIONAL'
            ],

            legend: {
                position: 'bottom'
            },

            responsive: [
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 260
                        }
                    }
                }
            ],

            tooltip: {

                y: {

                    formatter: function (value) {
                        return value + ' transaksi';
                    }

                }

            }

        };

        new ApexCharts(
            metodeChart,
            metodeOptions
        ).render();
    }

});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    lucide.createIcons();

});
</script>

@endif
</x-app-layout>