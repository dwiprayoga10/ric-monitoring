<?php

namespace App\Http\Controllers;

use App\Models\Swdkllj;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Swdkllj::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('nopol', 'like', "%{$search}%")
                  ->orWhere('nama_wp', 'like', "%{$search}%")
                  ->orWhere('nama_ric', 'like', "%{$search}%")
                  ->orWhere('id_petugas', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            if ($request->status == 'BELUM') {

                $query->where(function ($q) {

                    $q->whereNull('status_bayar')
                      ->orWhereRaw('TRIM(status_bayar) = ""')
                      ->orWhereRaw('UPPER(TRIM(status_bayar)) = "BELUM"')
                      ->orWhereRaw('UPPER(TRIM(status_bayar)) = "BELUM BAYAR"');

                });

            } else {

                $query->whereRaw(
                    'UPPER(TRIM(status_bayar)) = ?',
                    [strtoupper(trim($request->status))]
                );

            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATA TABLE
        |--------------------------------------------------------------------------
        */

        $data = $query
            ->latest()
            ->paginate(50)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | CARD STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalData = Swdkllj::count();

        $sudahBayar = Swdkllj::whereRaw(
            'UPPER(TRIM(status_bayar)) = ?',
            ['LUNAS']
        )->count();

        $dp = Swdkllj::whereRaw(
            'UPPER(TRIM(status_bayar)) = ?',
            ['DP']
        )->count();

        $belumBayar = Swdkllj::where(function ($q) {

            $q->whereNull('status_bayar')
              ->orWhereRaw('TRIM(status_bayar) = ""')
              ->orWhereRaw('UPPER(TRIM(status_bayar)) = "BELUM"')
              ->orWhereRaw('UPPER(TRIM(status_bayar)) = "BELUM BAYAR"');

        })->count();

        return view('dashboard.index', compact(
            'data',
            'totalData',
            'sudahBayar',
            'dp',
            'belumBayar'
        ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:csv,txt'
        ]);

        foreach ($request->file('files') as $file) {

            $path = $file->getRealPath();

            /*
            |--------------------------------------------------------------------------
            | BACA FILE
            |--------------------------------------------------------------------------
            */

            $content = file($path);

            /*
            |--------------------------------------------------------------------------
            | DETECT DELIMITER
            |--------------------------------------------------------------------------
            */

            $delimiter = ",";

            $firstLine = $content[0];

            if (substr_count($firstLine, ";") > substr_count($firstLine, ",")) {
                $delimiter = ";";
            }

            $handle = fopen($path, 'r');

            $headerSkipped = false;

            while (($row = fgetcsv($handle, 10000, $delimiter)) !== false) {

                /*
                |--------------------------------------------------------------------------
                | SKIP HEADER
                |--------------------------------------------------------------------------
                */

                if (!$headerSkipped) {
                    $headerSkipped = true;
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | SKIP ROW KOSONG
                |--------------------------------------------------------------------------
                */

                if (count(array_filter($row)) == 0) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | RAPIIKAN VALUE
                |--------------------------------------------------------------------------
                */

                $row = array_map(function ($value) {
                    return trim($value);
                }, $row);

                /*
                |--------------------------------------------------------------------------
                | FORMAT CSV
                |--------------------------------------------------------------------------
                |
                | 0  = NO
                | 1  = ID PETUGAS
                | 2  = NAMA RIC
                | 3  = NOPOL
                | 4  = NAMA WP
                | 5  = ALAMAT
                | 6  = MASA BERLAKU
                | 7  = GOL
                | 8  = NO HP
                | 9  = STATUS PENYERAHAN
                | 10 = STATUS KEPEMILIKAN
                | 11 = STATUS BAYAR
                |
                */

                /*
                |--------------------------------------------------------------------------
                | SKIP JIKA NOPOL KOSONG
                |--------------------------------------------------------------------------
                */

                if (empty($row[3])) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | RAPIIKAN STATUS BAYAR
                |--------------------------------------------------------------------------
                */

                $statusBayar = !empty($row[11])
                    ? strtoupper(trim($row[11]))
                    : 'BELUM BAYAR';

                /*
                |--------------------------------------------------------------------------
                | NORMALISASI STATUS
                |--------------------------------------------------------------------------
                */

                if (
                    $statusBayar == 'LUNAS' ||
                    $statusBayar == 'SUDAH BAYAR'
                ) {

                    $statusBayar = 'LUNAS';

                } elseif ($statusBayar == 'DP') {

                    $statusBayar = 'DP';

                } else {

                    $statusBayar = 'BELUM BAYAR';
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN DATABASE
                |--------------------------------------------------------------------------
                */

                Swdkllj::updateOrCreate(

                /*
                |--------------------------------------------------------------------------
                | DATA UNIK
                |--------------------------------------------------------------------------
                */

                [
                    'nopol' => strtoupper(trim($row[3] ?? null)),
                ],

                /*
                |--------------------------------------------------------------------------
                | DATA YANG DIUPDATE
                |--------------------------------------------------------------------------
                */

                [

                    'id_petugas' => $row[1] ?? null,

                    'nama_ric' => $row[2] ?? null,

                    'nama_wp' => $row[4] ?? null,

                    'alamat' => $row[5] ?? null,

                    'masa_berlaku' => !empty($row[6])
                        ? date('Y-m-d', strtotime($row[6]))
                        : null,

                    'gol' => $row[7] ?? null,

                    'no_hp' => $row[8] ?? null,
                    'status_penyerahan' => $row[9] ?? null,

                    'status_kepemilikan' => $row[10] ?? null,

                    'status_bayar' => $statusBayar,

                    'source_file' => $file->getClientOriginalName(),
                ]
            );
            }

            fclose($handle);
        }

        return back()->with('success', 'Import berhasil!');
    }

    public function data(Request $request)
    {
        $query = Swdkllj::query();

        // SEARCH
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('nopol', 'like', "%{$search}%")
                ->orWhere('nama_wp', 'like', "%{$search}%")
                ->orWhere('nama_ric', 'like', "%{$search}%")
                ->orWhere('id_petugas', 'like', "%{$search}%")
                ->orWhere('no_hp', 'like', "%{$search}%");

            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {

            if ($request->status == 'BELUM') {

                $query->where(function ($q) {

                    $q->whereNull('status_bayar')
                    ->orWhere('status_bayar', '')
                    ->orWhere('status_bayar', 'BELUM');

                });

            } else {

                $query->where('status_bayar', strtoupper($request->status));

            }
        }

        $data = $query
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view('data.index', compact('data'));
    }

    public function visualisasi()
    {
        /*
        |--------------------------------------------------------------------------
        | STATUS PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $lunas = Swdkllj::where('status_bayar', 'LUNAS')->count();

        $dp = Swdkllj::where('status_bayar', 'DP')->count();

        $belum = Swdkllj::where(function ($q) {

            $q->whereNull('status_bayar')
            ->orWhere('status_bayar', '')
            ->orWhere('status_bayar', 'BELUM')
            ->orWhere('status_bayar', 'BELUM BAYAR');

        })->count();

        /*
        |--------------------------------------------------------------------------
        | TOP PETUGAS
        |--------------------------------------------------------------------------
        */

        $petugas = Swdkllj::selectRaw('nama_ric, COUNT(*) as total')
            ->groupBy('nama_ric')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SOURCE FILE
        |--------------------------------------------------------------------------
        */

        $sourceFiles = Swdkllj::selectRaw('source_file, COUNT(*) as total')
            ->groupBy('source_file')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('visualisasi.index', compact(
            'lunas',
            'dp',
            'belum',
            'petugas',
            'sourceFiles'
        ));
    }

    public function laporan(Request $request)
{
    $query = Swdkllj::query();

    /*
    |--------------------------------------------------------------------------
    | FILTER STATUS
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        if ($request->status == 'BELUM') {

            $query->where(function ($q) {

                $q->whereNull('status_bayar')
                  ->orWhere('status_bayar', '')
                  ->orWhere('status_bayar', 'BELUM')
                  ->orWhere('status_bayar', 'BELUM BAYAR');

            });

        } else {

            $query->where('status_bayar', strtoupper($request->status));

        }
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER TANGGAL
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tanggal_awal')) {

        $query->whereDate(
            'created_at',
            '>=',
            $request->tanggal_awal
        );
    }

    if ($request->filled('tanggal_akhir')) {

        $query->whereDate(
            'created_at',
            '<=',
            $request->tanggal_akhir
        );
    }

    $data = $query
        ->latest()
        ->paginate(50)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | TOTAL
    |--------------------------------------------------------------------------
    */

    $total = $data->total();

    return view('laporan.index', compact(
        'data',
        'total'
    ));
}
}