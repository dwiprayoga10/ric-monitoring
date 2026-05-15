<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-800">
            Laporan SWDKLLJ
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-[95%] mx-auto space-y-6">

            <!-- FILTER -->
            <div class="bg-white rounded-3xl shadow border p-6">

                <form method="GET" action="{{ route('laporan') }}">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <div>

                            <label class="text-sm text-slate-600">
                                Status
                            </label>

                            <select
                                name="status"
                                class="w-full border rounded-2xl p-3 mt-2"
                            >
                                <option value="">Semua</option>

                                <option value="LUNAS"
                                    {{ request('status') == 'LUNAS' ? 'selected' : '' }}>
                                    LUNAS
                                </option>

                                <option value="DP"
                                    {{ request('status') == 'DP' ? 'selected' : '' }}>
                                    DP
                                </option>

                                <option value="BELUM"
                                    {{ request('status') == 'BELUM' ? 'selected' : '' }}>
                                    BELUM
                                </option>

                            </select>

                        </div>

                        <div>

                            <label class="text-sm text-slate-600">
                                Tanggal Awal
                            </label>

                            <input
                                type="date"
                                name="tanggal_awal"
                                value="{{ request('tanggal_awal') }}"
                                class="w-full border rounded-2xl p-3 mt-2"
                            >

                        </div>

                        <div>

                            <label class="text-sm text-slate-600">
                                Tanggal Akhir
                            </label>

                            <input
                                type="date"
                                name="tanggal_akhir"
                                value="{{ request('tanggal_akhir') }}"
                                class="w-full border rounded-2xl p-3 mt-2"
                            >

                        </div>

                        <div class="flex items-end gap-3">

                            <button
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-semibold"
                            >
                                Filter
                            </button>

                            <button
                                type="button"
                                onclick="window.print()"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-semibold"
                            >
                                Print
                            </button>

                        </div>

                    </div>

                </form>

            </div>

            <!-- CARD -->
            <div class="bg-white rounded-3xl shadow border p-6">

                <div class="flex justify-between items-center">

                    <div>

                        <h3 class="text-2xl font-bold text-slate-800">
                            Total Laporan
                        </h3>

                        <p class="text-slate-500 mt-1">
                            Data hasil filter laporan
                        </p>

                    </div>

                    <div class="text-5xl font-bold text-blue-600">
                        {{ $total }}
                    </div>

                </div>

            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-3xl shadow border p-6">

                <div class="overflow-x-auto rounded-2xl border">

                    <table class="w-full min-w-[1400px]">

                        <thead class="bg-slate-100">

                            <tr class="text-sm uppercase text-slate-600">

                                <th class="text-left px-4 py-4">NOPOL</th>
                                <th class="text-left px-4 py-4">NAMA WP</th>
                                <th class="text-left px-4 py-4">NO HP</th>
                                <th class="text-left px-4 py-4">STATUS</th>
                                <th class="text-left px-4 py-4">SOURCE FILE</th>
                                <th class="text-left px-4 py-4">TANGGAL</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($data as $item)

                                <tr class="border-b hover:bg-slate-50">

                                    <td class="px-4 py-4 font-semibold">
                                        {{ $item->nopol }}
                                    </td>

                                    <td class="px-4 py-4">
                                        {{ $item->nama_wp }}
                                    </td>

                                    <td class="px-4 py-4">
                                        {{ $item->no_hp }}
                                    </td>

                                    <td class="px-4 py-4">

                                        @if($item->status_bayar == 'LUNAS')

                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                                LUNAS
                                            </span>

                                        @elseif($item->status_bayar == 'DP')

                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                                DP
                                            </span>

                                        @else

                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                                BELUM
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-4 py-4 text-slate-500 text-sm">
                                        {{ $item->source_file }}
                                    </td>

                                    <td class="px-4 py-4 text-slate-500 text-sm">
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center py-10 text-slate-400">
                                        Tidak ada data laporan
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-6">
                    {{ $data->links() }}
                </div>

            </div>

        </div>

    </div>

</x-app-layout>