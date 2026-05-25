<x-app-layout>

<div class="page-wrapper">

    {{-- BUTTON PRINT --}}
    <div class="toolbar no-print">
        <button
            onclick="window.print()"
            class="print-btn"
        >
            Print PDF
        </button>
    </div>

    {{-- REPORT --}}
    <div class="report-page">

        {{-- HEADER --}}
        <div class="report-header">

            <h1>
                REKAP LAPORAN PETUGAS LAPANGAN (RIC)
            </h1>

            <p>
                Pengukuran Keberhasilan Penyampaian Surat Pemberitahuan SWDKLLJ
            </p>

            <hr>

        </div>

        {{-- IDENTITAS PETUGAS --}}
        <table class="report-table mb-6">

            <tbody>

                <tr>
                    <td class="label-cell">
                        Nama Petugas
                    </td>

                    <td>
                        {{ $laporan['namaRic'] ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td class="label-cell">
                        ID RIC
                    </td>

                    <td>
                        {{ $laporan['idPetugas'] ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td class="label-cell font-bold">
                        Total Target Surat
                    </td>

                    <td class="font-bold">
                        {{ number_format($laporan['totalTarget'] ?? 0) }}
                    </td>
                </tr>

            </tbody>

        </table>

        {{-- A. STATUS PENYERAHAN --}}
        <div class="section-title">
            A. Rekap Status Penyerahan
        </div>

        <table class="report-table">

            <thead>

                <tr>
                    <th width="70">
                        No
                    </th>

                    <th>
                        Status Penyerahan
                    </th>

                    <th width="150">
                        Jumlah
                    </th>
                </tr>

            </thead>

            <tbody>

                @forelse($laporan['statusPenyerahan'] as $status => $jumlah)

                    <tr>

                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $status }}
                        </td>

                        <td class="text-center">
                            {{ number_format($jumlah) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="empty-row">
                            Tidak ada data
                        </td>
                    </tr>

                @endforelse

                <tr class="font-bold">

                    <td colspan="2" class="text-center">
                        JUMLAH
                    </td>

                    <td class="text-center">
                        {{ number_format($laporan['totalPenyerahan'] ?? 0) }}
                    </td>

                </tr>

            </tbody>

        </table>

        {{-- B. STATUS KEPEMILIKAN --}}
        <div class="section-title">
            B. Rekap Status Kepemilikan
        </div>

        <table class="report-table">

            <thead>

                <tr>
                    <th width="70">
                        No
                    </th>

                    <th>
                        Status Kepemilikan
                    </th>

                    <th width="150">
                        Jumlah
                    </th>
                </tr>

            </thead>

            <tbody>

                @forelse($laporan['statusKepemilikan'] as $status => $jumlah)

                    <tr>

                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $status }}
                        </td>

                        <td class="text-center">
                            {{ number_format($jumlah) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="empty-row">
                            Tidak ada data
                        </td>
                    </tr>

                @endforelse

                <tr class="font-bold">

                    <td colspan="2" class="text-center">
                        JUMLAH
                    </td>

                    <td class="text-center">
                        {{ number_format(collect($laporan['statusKepemilikan'])->sum()) }}
                    </td>

                </tr>

            </tbody>

        </table>

        {{-- C. STATUS PEMBAYARAN --}}
        <div class="section-title">
            C. Rekap Status Pembayaran
        </div>

        <table class="report-table">

            <thead>

                <tr>
                    <th width="70">
                        No
                    </th>

                    <th>
                        Status Pembayaran
                    </th>

                    <th width="150">
                        Jumlah
                    </th>
                </tr>

            </thead>

            <tbody>

                @forelse($laporan['statusBayar'] as $status => $jumlah)

                    <tr>

                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $status }}
                        </td>

                        <td class="text-center">
                            {{ number_format($jumlah) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="empty-row">
                            Tidak ada data
                        </td>
                    </tr>

                @endforelse

                <tr class="font-bold">

                    <td colspan="2">
                        TOTAL NOMINAL BAYAR
                    </td>

                    <td class="text-center">
                        Rp {{ number_format($laporan['nominalBayar'] ?? 0, 0, ',', '.') }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<style>

@page{
    size:A4 portrait;
    margin:8mm 12mm 12mm 12mm;
}

/* ===========================
   GLOBAL
=========================== */

body{
    font-family:Arial, sans-serif;
    background:#e5e7eb;
    margin:0;
    padding:0;
}

.page-wrapper{
    max-width:1200px;
    margin:auto;
    padding:30px 16px;
}

/* ===========================
   TOOLBAR
=========================== */

.toolbar{
    display:flex;
    justify-content:flex-end;
    margin-bottom:20px;
}

.print-btn{
    background:#16a34a;
    color:#fff;
    border:none;
    padding:12px 22px;
    border-radius:8px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:.2s;
}

.print-btn:hover{
    background:#15803d;
}

/* ===========================
   REPORT CONTAINER
=========================== */

.report-page{
    width:210mm;
    min-height:297mm;
    background:#fff;
    margin:auto;
    padding:16mm;
    box-sizing:border-box;
    border:1px solid #d1d5db;
    box-shadow:0 2px 12px rgba(0,0,0,.08);
}

/* ===========================
   HEADER
=========================== */

.report-header{
    text-align:center;
    margin-bottom:20px;
}

.report-header h1{
    margin:0;
    font-size:18px;
    font-weight:700;
    text-transform:uppercase;
}

.report-header p{
    margin-top:6px;
    margin-bottom:12px;
    font-size:13px;
    color:#555;
}

.report-header hr{
    border:1px solid #000;
    margin:0;
}

/* ===========================
   SECTION TITLE
=========================== */

.section-title{
    font-weight:700;
    font-size:14px;
    margin-top:20px;
    margin-bottom:10px;
}

/* ===========================
   TABLE
=========================== */

.report-table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:20px;
}

.report-table th,
.report-table td{
    border:1px solid #000;
    padding:8px 10px;
    font-size:13px;
    vertical-align:middle;
}

.report-table th{
    text-align:center;
    font-weight:700;
}

.label-cell{
    width:220px;
    font-weight:600;
}

.text-center{
    text-align:center;
}

.font-bold{
    font-weight:700;
}

.empty-row{
    text-align:center;
    color:#666;
    padding:14px;
}

/* ===========================
   PRINT FIX
=========================== */

tr,
td,
th{
    page-break-inside:avoid !important;
}

table{
    page-break-inside:auto;
}

thead{
    display:table-header-group;
}

tfoot{
    display:table-footer-group;
}

/* ===========================
   PRINT MODE
=========================== */

@media print {

    html,
    body{
        width:210mm;
        height:auto;
        background:#fff !important;
        margin:0 !important;
        padding:0 !important;
    }

    /* sembunyikan semua */
    body *{
        visibility:hidden;
    }

    /* tampilkan hanya laporan */
    .report-page,
    .report-page *{
        visibility:visible;
    }

    /* hilangkan button */
    .no-print{
        display:none !important;
    }

    /* posisi laporan */
    .report-page{
        position:absolute;
        left:0;
        top:-8mm;
        width:100%;
        min-height:auto;
        margin:0 !important;
        padding:0 !important;
        border:none !important;
        box-shadow:none !important;
        overflow:visible;
    }

    /* warna tetap muncul */
    *{
        -webkit-print-color-adjust:exact;
        print-color-adjust:exact;
    }
}

</style>
</x-app-layout>