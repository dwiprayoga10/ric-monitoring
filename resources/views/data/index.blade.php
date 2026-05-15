<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-800">
            Data SWDKLLJ
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-[98%] mx-auto">

            <!-- SEARCH -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 mb-6">

                <form method="GET" action="{{ route('data.swdkllj') }}">

                    <div class="flex flex-col md:flex-row gap-4">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nopol / nama / no hp..."
                            class="w-full border border-slate-300 rounded-2xl p-3"
                        >

                        <select
                            name="status"
                            class="border border-slate-300 rounded-2xl p-3"
                        >
                            <option value="">Semua Status</option>
                            <option value="LUNAS" {{ request('status') == 'LUNAS' ? 'selected' : '' }}>
                                LUNAS
                            </option>
                            <option value="DP" {{ request('status') == 'DP' ? 'selected' : '' }}>
                                DP
                            </option>
                            <option value="BELUM" {{ request('status') == 'BELUM' ? 'selected' : '' }}>
                                BELUM
                            </option>
                        </select>

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl font-semibold"
                        >
                            Search
                        </button>

                    </div>

                </form>

            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">

                <div class="overflow-x-auto rounded-2xl border border-slate-200">

                    <table class="w-full min-w-[1900px]">

                        <thead class="bg-slate-100">

                            <tr class="text-slate-600 text-sm uppercase">

                                <th class="text-left py-4 px-4">NOPOL</th>
                                <th class="text-left py-4 px-4">NAMA WP</th>
                                <th class="text-left py-4 px-4">NO HP</th>
                                <th class="text-left py-4 px-4">STATUS</th>
                                <th class="text-left py-4 px-4">SOURCE FILE</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($data as $item)

                                <tr class="border-b hover:bg-slate-50">

                                    <td class="py-4 px-4 font-semibold">
                                        {{ $item->nopol }}
                                    </td>

                                    <td class="py-4 px-4">
                                        {{ $item->nama_wp }}
                                    </td>

                                    <td class="py-4 px-4">
                                        {{ $item->no_hp }}
                                    </td>

                                    <td class="py-4 px-4">

                                        @php
                                            $status = strtoupper($item->status_bayar ?? 'BELUM');
                                        @endphp

                                        @if($status == 'LUNAS')

                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                                LUNAS
                                            </span>

                                        @elseif($status == 'DP')

                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                                DP
                                            </span>

                                        @else

                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                                BELUM
                                            </span>

                                        @endif

                                    </td>

                                    <td class="py-4 px-4 text-sm text-slate-500">
                                        {{ $item->source_file }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center py-10 text-slate-400">
                                        Tidak ada data
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