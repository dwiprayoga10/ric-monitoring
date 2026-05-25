{{-- laporan blade --}}
<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">
                    Laporan SWDKLLJ Petugas
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Rekap monitoring seluruh petugas lapangan
                </p>

            </div>

            <div class="flex items-center gap-3 bg-slate-100 border border-slate-200 px-5 py-3 rounded-2xl">

                <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>

                <div>

                    <p class="text-xs text-slate-500">
                        Total Petugas
                    </p>

                    <p class="font-semibold text-slate-800">
                        {{ $totalPetugas }}
                    </p>

                </div>

            </div>

        </div>

    </x-slot>

    <div class="py-6">

        <div class="page-container space-y-6">

            
            {{-- SUMMARY --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

                <div class="card-ui rounded-[28px] p-5">

                    <p class="text-sm text-slate-500">
                        Total Target
                    </p>

                    <h3 class="text-3xl font-bold text-slate-800 mt-2">
                        {{ number_format($totalTarget) }}
                    </h3>

                </div>

                <div class="card-ui rounded-[28px] p-5">

                    <p class="text-sm text-slate-500">
                        Total Sudah Bayar
                    </p>

                    <h3 class="text-3xl font-bold text-green-600 mt-2">
                        {{ number_format($totalsudah_bayar) }}
                    </h3>

                </div>

                <div class="card-ui rounded-[28px] p-5">

                    <p class="text-sm text-slate-500">
                        Belum Bayar
                    </p>

                    <h3 class="text-3xl font-bold text-red-500 mt-2">
                        {{ number_format($totalBelum) }}
                    </h3>

                </div>

                <div class="card-ui rounded-[28px] p-5">

                    <p class="text-sm text-slate-500">
                        Total Nominal
                    </p>

                    <h3 class="text-2xl font-bold text-blue-600 mt-2">
                        Rp {{ number_format($grandNominal, 0, ',', '.') }}
                    </h3>

                </div>

            </div>

            {{-- TABLE --}}
            <div class="card-ui rounded-[28px] p-5">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">

    <div>

        <h3 class="font-bold text-lg text-slate-800">
            Rekap Kinerja Petugas
        </h3>

        <p class="text-sm text-slate-500">
            Monitoring penyelesaian SWDKLLJ petugas lapangan
        </p>

    </div>

    {{-- FILTER PETUGAS --}}
    <form
        method="GET"
        action="{{ route('laporan') }}"
        class="flex items-center gap-2"
    >

        <select
            name="petugas"
            onchange="this.form.submit()"
            class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm min-w-[260px]"
        >

            <option value="">
                Semua Petugas
            </option>

            @foreach($petugasList as $petugas)

                <option
                    value="{{ trim($petugas) }}"
                    @selected(
                        trim(request('petugas'))
                        ==
                        trim($petugas)
                    )
                >
                    {{ trim($petugas) }}
                </option>

            @endforeach

        </select>

        @if(request('petugas'))

            <a
                href="{{ route('laporan') }}"
                class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-3 rounded-2xl text-sm font-medium"
            >
                Reset
            </a>

        @endif

    </form>

</div>

                <div class="overflow-hidden rounded-[24px] border border-slate-200">

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1900px]">

                            <thead class="bg-slate-100">

                                <tr class="text-xs uppercase text-slate-600">

                                    <th class="px-4 py-4 text-left">
                                        Petugas
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Target
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        DP (Mobil)
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        CI (Motor)
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Penyerahan
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Kepemilikan
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Sudah Bayar
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Belum Bayar

                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Online
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Konvensional
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Nominal
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Progress
                                    </th>

                                    <th class="px-4 py-4 text-center">
                                        Detail
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="bg-white">

                                @forelse($laporanPetugas as $item)

                                <tr class="border-b border-slate-100 hover:bg-slate-50">

                                    <td class="px-4 py-4">

                                        <div>

                                            <p class="font-semibold text-slate-800">
                                                {{ $item->nama_ric }}
                                            </p>

                                            <p class="text-xs text-slate-500">
                                                {{ $item->id_petugas }}
                                            </p>

                                        </div>

                                    </td>

                                    <td class="text-center font-semibold">
                                        {{ $item->total_target }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->total_dp }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->total_ci }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->total_penyerahan }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->total_kepemilikan }}
                                    </td>

                                    <td class="text-center text-green-600 font-bold">
                                        {{ $item->sudah_bayar }}
                                    </td>

                                    <td class="text-center text-red-500 font-bold">
                                        {{ $item->belum }}
                                    </td>

                                    <td class="text-center text-blue-600 font-semibold">
                                        {{ $item->online }}
                                    </td>

                                    <td class="text-center text-violet-600 font-semibold">
                                        {{ $item->konvensional }}
                                    </td>

                                    <td class="text-center font-bold text-slate-800">
                                        Rp {{ number_format($item->total_nominal,0,',','.') }}
                                    </td>

                                    <td class="px-4 py-4">

                                        <div class="w-[140px] mx-auto">

                                            <div class="flex justify-between text-xs mb-1">
                                                <span>
                                                    {{ $item->persen_penyerahan }}%
                                                </span>
                                            </div>

                                            <div class="h-2 bg-slate-200 rounded-full overflow-hidden">

                                                <div
                                                    class="h-full bg-green-500 rounded-full"
                                                    style="width: {{ $item->persen_penyerahan }}%"
                                                ></div>

                                            </div>

                                        </div>

                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="{{ route('laporan.petugas', $item->nama_ric) }}"
                                            class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium"
                                        >
                                            Detail
                                        </a>

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td
                                        colspan="13"
                                        class="text-center py-10 text-slate-400"
                                    >
                                        Tidak ada data laporan
                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>