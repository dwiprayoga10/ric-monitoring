<x-app-layout>

<div class="max-w-6xl mx-auto py-8 px-4">

    {{-- BUTTON PRINT --}}
    <div class="flex justify-end mb-5 no-print">

        <button
            onclick="window.print()"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold shadow"
        >
            Print PDF
        </button>

    </div>

    <div class="bg-white p-6 border border-slate-300 report-page">

        {{-- HEADER --}}
        <div class="text-center mb-5">

            <h1 class="font-bold text-lg uppercase">
                REKAP LAPORAN PETUGAS LAPANGAN (RIC)
            </h1>

            <p class="text-sm">
                Pengukuran Keberhasilan Penyampaian Surat Pemberitahuan SWKDLLJ
            </p>

        </div>

        {{-- IDENTITAS --}}
        <table class="w-full border border-black text-sm mb-6">

            <tr>
                <td class="border border-black px-2 py-1 w-[220px]">
                    Nama Petugas
                </td>

                <td class="border border-black px-2 py-1">
                    {{ $laporan['namaRic'] }}
                </td>
            </tr>

            <tr>
                <td class="border border-black px-2 py-1">
                    ID RIC
                </td>

                <td class="border border-black px-2 py-1">
                    {{ $laporan['idPetugas'] }}
                </td>
            </tr>

            <tr>
                <td class="border border-black px-2 py-1 font-semibold">
                    Total Target Surat
                </td>

                <td class="border border-black px-2 py-1 font-semibold">
                    {{ $laporan['totalTarget'] }}
                </td>
            </tr>

        </table>

        {{-- A. STATUS PENYERAHAN --}}
        <div class="font-bold text-sm mb-2">
            A. REKAP STATUS PENYERAHAN
        </div>

        <table class="w-full border border-black text-sm mb-6">

            <thead>

                <tr>
                    <th class="border border-black p-2 w-[70px]">
                        NO
                    </th>

                    <th class="border border-black p-2">
                        STATUS PENYERAHAN
                    </th>

                    <th class="border border-black p-2 w-[150px]">
                        JUMLAH
                    </th>
                </tr>

            </thead>

            <tbody>

                @foreach($laporan['statusPenyerahan'] as $status => $jumlah)

                <tr>

                    <td class="border border-black text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="border border-black px-2 py-1">
                        {{ $status }}
                    </td>

                    <td class="border border-black text-center">
                        {{ $jumlah }}
                    </td>

                </tr>

                @endforeach

                <tr class="font-bold">

                    <td colspan="2" class="border border-black px-2 py-1 text-center">
                        JUMLAH
                    </td>

                    <td class="border border-black text-center">
                        {{ $laporan['totalPenyerahan'] }}
                    </td>

                </tr>

            </tbody>

        </table>

        {{-- B. STATUS KEPEMILIKAN --}}
        <div class="font-bold text-sm mb-2">
            B. REKAP STATUS KEPEMILIKAN
        </div>

        <table class="w-full border border-black text-sm mb-6">

            <thead>

                <tr>
                    <th class="border border-black p-2 w-[70px]">
                        NO
                    </th>

                    <th class="border border-black p-2">
                        STATUS KEPEMILIKAN
                    </th>

                    <th class="border border-black p-2 w-[150px]">
                        JUMLAH
                    </th>
                </tr>

            </thead>

            <tbody>

                @foreach($laporan['statusKepemilikan'] as $status => $jumlah)

                <tr>

                    <td class="border border-black text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="border border-black px-2 py-1">
                        {{ $status }}
                    </td>

                    <td class="border border-black text-center">
                        {{ $jumlah }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        {{-- C. STATUS PEMBAYARAN --}}
        <div class="font-bold text-sm mb-2">
            C. REKAP STATUS PEMBAYARAN
        </div>

        <table class="w-full border border-black text-sm mb-6">

            <thead>

                <tr>
                    <th class="border border-black p-2 w-[70px]">
                        NO
                    </th>

                    <th class="border border-black p-2">
                        STATUS PEMBAYARAN
                    </th>

                    <th class="border border-black p-2 w-[150px]">
                        JUMLAH
                    </th>
                </tr>

            </thead>

            <tbody>

                @foreach($laporan['statusBayar'] as $status => $jumlah)

                <tr>

                    <td class="border border-black text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="border border-black px-2 py-1">
                        {{ $status }}
                    </td>

                    <td class="border border-black text-center">
                        {{ $jumlah }}
                    </td>

                </tr>

                @endforeach

                <tr class="font-bold">

                    <td colspan="2" class="border border-black px-2 py-1">
                        TOTAL NOMINAL BAYAR
                    </td>

                    <td class="border border-black text-center">
                        Rp {{ number_format($laporan['nominalBayar'], 0, ',', '.') }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<style>
.report-page{
    min-height: 1120px;
    font-family: Arial, sans-serif;
}

table{
    border-collapse: collapse;
}

@media print {

    .no-print{
        display:none !important;
    }

    body{
        background:white !important;
    }

    .report-page{
        border:none !important;
        box-shadow:none !important;
        margin:0;
        padding:0;
    }
}
</style>

</x-app-layout>