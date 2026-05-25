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

            <!-- SYNC GOOGLE SHEET -->
<div class="card-ui rounded-[28px] p-6">

    <div class="flex items-center justify-between flex-col lg:flex-row gap-5">

        <div class="flex items-center gap-4">

            <div class="h-14 w-14 rounded-2xl bg-blue-100 flex items-center justify-center">

    <i
        data-lucide="database"
        class="w-6 h-6 text-blue-600"
    ></i>

</div>

            <div>

    <h3 class="font-bold text-lg text-slate-800">
        Import Data
    </h3>

    <p class="text-sm text-slate-500">
        Upload file .xlsx, .xls, .csv
    </p>

    {{-- LAST UPDATE --}}
    @if($lastUpdate)

        <div class="mt-3">

            <p class="text-xs font-medium text-slate-500">
                Update terakhir
            </p>

            <div class="flex items-center gap-2 mt-1">

                <span class="text-xs text-slate-600">
                    {{
    \Carbon\Carbon::parse(
        $lastUpdate['time']
    )
    ->timezone(
        'Asia/Jakarta'
    )
    ->translatedFormat(
        'd F Y • H:i'
    )
}} WIB
                    
                
                </span>

                <span class="
                    text-[11px]
                    bg-blue-100
                    text-blue-700
                    px-2 py-1
                    rounded-full
                    font-medium
                ">
                    {{
                        $lastUpdate['source']
                    }}
                </span>

            </div>

        </div>

    @endif

</div>

        </div>

        <div class="flex flex-col sm:flex-row gap-3">

    <!-- IMPORT EXCEL -->
    <form
        id="importExcelForm"
        enctype="multipart/form-data"
    >
        @csrf

        <label
            for="excelFile"
            class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-semibold transition flex items-center gap-2"
        >
            <i
                data-lucide="file-spreadsheet"
                class="w-4 h-4"
            ></i>

            Upload File
        </label>

        <input
    id="excelFile"
    type="file"
    name="files[]"
    multiple
    accept=".xlsx,.xls,.csv,.txt"
    class="hidden"
    onchange="importExcel()"
>
    </form>

    {{-- <!-- SYNC -->
    <button
        id="syncBtn"
        onclick="syncSpreadsheet()"
        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-2xl font-semibold transition flex items-center gap-2"
    >
        <i
            data-lucide="refresh-cw"
            class="w-4 h-4"
        ></i>

        Sync Sekarang
    </button> --}}

</div>

    </div>

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
                                {{ $sudah_bayar }}
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
                                    {{ $sudah_bayar }}
                                </span>

                            </div>

                            <div class="w-full h-2.5 rounded-full bg-slate-200 overflow-hidden">

                                <div
                                    class="bg-green-500 h-full rounded-full"
                                    style="width: {{ $totalData > 0 ? ($sudah_bayar / $totalData) * 100 : 0 }}%"
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
                                    Sudah Bayar
                                </p>

                                <h4 class="font-bold text-xl text-green-600 mt-1">
                                    {{ $sudah_bayar }}
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
                            Data kendaraan hasil import file
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

                                           @if($status == 'SUDAH BAYAR')

    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
        SUDAH BAYAR
    </span>

@else

    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">
        BELUM BAYAR
    </span>

@endif

                                        </td>
                                        <!-- METODE PEMBAYARAN -->
<td class="py-4 px-4 text-center">

    @php
        $metode = strtolower($item->metode_pembayaran ?? '');
    @endphp

    @if($status !== 'SUDAH BAYAR')

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

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    /*
    |--------------------------------------------------------------------------
    | CHART
    |--------------------------------------------------------------------------
    */

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
                {{ $sudah_bayar }},
                {{ $belumBayar }}
            ]
        }],

        xaxis: {
            categories: [
                'SUDAH BAYAR',
                'BELUM BAYAR'
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

    const chart =
        new ApexCharts(
            document.querySelector("#chart"),
            options
        );

    chart.render();


    /*
    |--------------------------------------------------------------------------
    | SYNC SPREADSHEET
    |--------------------------------------------------------------------------
    */

   /* async function syncSpreadsheet() {

        const btn =
            document.getElementById(
                'syncBtn'
            );

        btn.disabled = true;

        btn.innerHTML =
            'Syncing...';

        Swal.fire({
            title:
                'Sinkronisasi Data',
            text:
                'Sedang mengambil data spreadsheet...',
            allowOutsideClick:
                false,
            allowEscapeKey:
                false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {

            const response =
                await fetch(
                    '/api/sync-spreadsheet'
                );

            const result =
                await response.json();

            Swal.close();

            if (
                result.success
            ) {

                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Sync Berhasil',

                    html: `
                        <div style="font-size:14px">
                            <p>Total data:</p>

                            <h2 style="
                                font-size:32px;
                                font-weight:700;
                                color:#2563EB;
                                margin-top:8px;
                            ">
                                ${result.total}
                            </h2>
                        </div>
                    `,

                    confirmButtonText:
                        'OK',

                    confirmButtonColor:
                        '#2563EB',

                    borderRadius:
                        24

                }).then(() => {

                    location.reload();

                });

            } else {

                Swal.fire({

                    icon:
                        'error',

                    title:
                        'Sync Gagal',

                    text:
                        result.message
                        ||
                        'Sync gagal',

                    confirmButtonColor:
                        '#DC2626',

                    borderRadius:
                        24
                });
            }

        } catch (error) {

            console.error(
                error
            );

            Swal.fire({

                icon:
                    'error',

                title:
                    'Oops...',

                text:
                    'Terjadi kesalahan',

                confirmButtonColor:
                    '#DC2626',

                borderRadius:
                    24
            });

        } finally {

            btn.disabled =
                false;

            btn.innerHTML =
                'Sync Sekarang';
        }
    }*/


    /*
    |--------------------------------------------------------------------------
    | IMPORT EXCEL
    |--------------------------------------------------------------------------
    */

    async function importExcel() {

        const fileInput =
            document.getElementById(
                'excelFile'
            );

        if (
            !fileInput.files.length
        ) {
            return;
        }

        const formData =
            new FormData();

        for (
    let i = 0;
    i < fileInput.files.length;
    i++
) {
    formData.append(
        'files[]',
        fileInput.files[i]
    );
}

        const btn =
            document.querySelector(
                'label[for="excelFile"]'
            );

        const oldText =
            btn.innerHTML;

        btn.innerHTML =
            'Importing...';

        btn.style.pointerEvents =
            'none';

        Swal.fire({

            title:
                'Import Data',

            text:
                'Sedang memproses file...',

            allowOutsideClick:
                false,

            allowEscapeKey:
                false,

            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {

            const response =
                await fetch(
                    '/import-excel',
                    {
                        method:
                            'POST',

                        headers: {
                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .content
                        },

                        body:
                            formData
                    }
                );

            const result =
                await response.json();

            Swal.close();

            if (
                result.success
            ) {

                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Import data berhasil',

                    html: `
                        <div style="font-size:14px">
                            <p>Total data:</p>

                            <h2 style="
                                font-size:32px;
                                font-weight:700;
                                color:#2563EB;
                                margin-top:8px;
                            ">
                                ${result.total}
                            </h2>
                        </div>
                    `,

                    confirmButtonText:
                        'OK',

                    confirmButtonColor:
                        '#2563EB',

                    borderRadius:
                        24

                }).then(() => {

                    location.reload();

                });

            } else {

                Swal.fire({

                    icon:
                        'error',

                    title:
                        'Import Gagal',

                    text:
                        result.message
                        ||
                        'Import gagal',

                    confirmButtonColor:
                        '#DC2626',

                    borderRadius:
                        24
                });
            }

        } catch (error) {

            console.error(
                error
            );

            Swal.fire({

                icon:
                    'error',

                title:
                    'Oops...',

                text:
                    'Terjadi kesalahan saat import',

                confirmButtonColor:
                    '#DC2626',

                borderRadius:
                    24
            });

        } finally {

            btn.innerHTML =
                oldText;

            btn.style.pointerEvents =
                'auto';

            fileInput.value =
                '';
        }
    }

</script>

</x-app-layout>