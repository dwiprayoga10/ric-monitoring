<?php

namespace App\Http\Controllers;

use App\Models\Swdkllj;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

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
    set_time_limit(0);
    ini_set('memory_limit', '1024M');

    $request->validate([
        'files.*' => 'required|mimes:csv,txt,xls,xlsx'
    ]);

    $totalImported = 0;
    $totalSkipped = 0;

    /*
    |--------------------------------------------------------------------------
    | SHEET YANG DIPROSES
    |--------------------------------------------------------------------------
    */

    $allowedSheets = [
        'CEK DATA I fix',
        'CEK DATA II fix',
        'CEK DATA III FIX',
    ];

    /*
    |--------------------------------------------------------------------------
    | BUFFER UPSERT
    |--------------------------------------------------------------------------
    */

    $buffer = [];

    $flushBuffer = function () use (&$buffer) {

    if (empty($buffer)) {
        return;
    }

    foreach ($buffer as $item) {

        Swdkllj::updateOrCreate(

            /*
            |--------------------------------------------------------------------------
            | CARI BERDASARKAN NOPOL
            |--------------------------------------------------------------------------
            */

            [
                'nopol' => $item['nopol'],
            ],

            /*
            |--------------------------------------------------------------------------
            | DATA UPDATE
            |--------------------------------------------------------------------------
            */

            [

                'no' => $item['no'],

                'id_petugas' => $item['id_petugas'],

                'nama_ric' => $item['nama_ric'],

                'nama_wp' => $item['nama_wp'],

                'alamat' => $item['alamat'],

                'masa_berlaku' => $item['masa_berlaku'],

                'gol' => $item['gol'],

                'no_hp' => $item['no_hp'],

                'status_penyerahan' =>
                    $item['status_penyerahan'],

                'status_kepemilikan' =>
                    $item['status_kepemilikan'],

                'status_bayar' =>
                    $item['status_bayar'],

                'metode_pembayaran' =>
                    $item['metode_pembayaran'],

                'nominal_bayar' =>
                    $item['nominal_bayar'],

                'source_file' =>
                    $item['source_file'],
            ]
        );
    }

    $buffer = [];
};

    /*
    |--------------------------------------------------------------------------
    | FUNCTION PROCESS ROW
    |--------------------------------------------------------------------------
    */

    $processRows = function (
        array $rows,
        string $sourceFile
    ) use (
        &$buffer,
        &$flushBuffer,
        &$totalImported,
        &$totalSkipped
    ) {

        $headerFound = false;

        foreach ($rows as $row) {

            /*
            |--------------------------------------------------------------------------
            | RAPIIKAN DATA
            |--------------------------------------------------------------------------
            */

            $row = array_map(function ($value) {
                return trim((string) ($value ?? ''));
            }, $row);

            /*
            |--------------------------------------------------------------------------
            | SKIP KOSONG
            |--------------------------------------------------------------------------
            */

            if (count(array_filter($row)) === 0) {
                $totalSkipped++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | DETECT HEADER
            |--------------------------------------------------------------------------
            */

            if (!$headerFound) {

                $isHeader =
                    strtoupper($row[0] ?? '') === 'NO' &&
                    strtoupper($row[1] ?? '') === 'ID PETUGAS' &&
                    strtoupper($row[2] ?? '') === 'NAMA RIC' &&
                    strtoupper($row[3] ?? '') === 'NOPOL' &&
                    strtoupper($row[4] ?? '') === 'NAMA WP';

                if ($isHeader) {
                    $headerFound = true;
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | SKIP HEADER NYANGKUT
            |--------------------------------------------------------------------------
            */

            if (
                strtoupper($row[0] ?? '') === 'NO' ||
                strtoupper($row[3] ?? '') === 'NOPOL'
            ) {
                $totalSkipped++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI WAJIB
            |--------------------------------------------------------------------------
            */

            $idPetugas = trim($row[1] ?? '');
            $nopol = strtoupper(
    preg_replace(
        '/[^A-Z0-9]/',
        '',
        trim($row[3] ?? '')
    )
);
            $namaWp = trim($row[4] ?? '');

            if (
                empty($idPetugas) ||
                empty($nopol) ||
                empty($namaWp)
            ) {
                $totalSkipped++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | STATUS BAYAR
            |--------------------------------------------------------------------------
            */

            $statusBayar = strtoupper(
                trim($row[11] ?? '')
            );

            if (
                $statusBayar === 'LUNAS' ||
                $statusBayar === 'SUDAH BAYAR'
            ) {

                $statusBayar = 'LUNAS';

            } elseif ($statusBayar === 'DP') {

                $statusBayar = 'DP';

            } else {

                $statusBayar = 'BELUM BAYAR';
            }

            /*
            |--------------------------------------------------------------------------
            | FORMAT MASA BERLAKU
            |--------------------------------------------------------------------------
            */

            $masaBerlaku = null;

            try {

                $rawTanggal = $row[6] ?? '';

                if (is_numeric($rawTanggal)) {

                    $masaBerlaku =
                        ExcelDate::excelToDateTimeObject(
                            $rawTanggal
                        )->format('Y-m-d');

                } else {

                    $rawTanggal = trim($rawTanggal);

                    if (!empty($rawTanggal)) {

                        $bulan = [
                            'Januari' => 'January',
                            'Februari' => 'February',
                            'Maret' => 'March',
                            'April' => 'April',
                            'Mei' => 'May',
                            'Juni' => 'June',
                            'Juli' => 'July',
                            'Agustus' => 'August',
                            'September' => 'September',
                            'Oktober' => 'October',
                            'November' => 'November',
                            'Desember' => 'December',
                        ];

                        $rawTanggal = str_replace(
                            array_keys($bulan),
                            array_values($bulan),
                            $rawTanggal
                        );

                        $masaBerlaku =
                            \Carbon\Carbon::createFromFormat(
                                'd F Y',
                                $rawTanggal
                            )->format('Y-m-d');
                    }
                }

            } catch (\Throwable $e) {

                $masaBerlaku = null;
            }

            /*
            |--------------------------------------------------------------------------
            | NOMINAL
            |--------------------------------------------------------------------------
            */

            $nominalBayar = null;

            if (!empty($row[12])) {

                $cleanNominal = preg_replace(
                    '/[^0-9]/',
                    '',
                    $row[12]
                );

                $nominalBayar =
                    is_numeric($cleanNominal)
                    ? (float) $cleanNominal
                    : null;
            }

            /*
            |--------------------------------------------------------------------------
            | METODE PEMBAYARAN
            |--------------------------------------------------------------------------
            */

            $metodePembayaran = strtolower(
                trim($row[13] ?? '')
            );

            $metodePembayaran = str_replace(
                ' ',
                '',
                $metodePembayaran
            );

            if ($metodePembayaran === 'online') {

                $metodePembayaran = 'online';

            } else {

                $metodePembayaran = 'konvensional';
            }

            /*
            |--------------------------------------------------------------------------
            | BUFFER DATA
            |--------------------------------------------------------------------------
            */

            $buffer[] = [

                'nopol' => $nopol,

                'no' => is_numeric($row[0] ?? null)
                    ? (int) $row[0]
                    : null,

                'id_petugas' => $idPetugas,

                'nama_ric' => $row[2] ?? null,

                'nama_wp' => $namaWp,

                'alamat' => $row[5] ?? null,

                'masa_berlaku' => $masaBerlaku,

                'gol' => $row[7] ?? null,

                'no_hp' => $row[8] ?? null,

                'status_penyerahan' => $row[9] ?? null,

                'status_kepemilikan' => $row[10] ?? null,

                'status_bayar' => $statusBayar,

                'metode_pembayaran' => $metodePembayaran,

                'nominal_bayar' => $nominalBayar,

                'source_file' => $sourceFile,

                'updated_at' => now(),

                
            ];

            $totalImported++;

            /*
            |--------------------------------------------------------------------------
            | AUTO FLUSH
            |--------------------------------------------------------------------------
            */

            if (count($buffer) >= 500) {
                $flushBuffer();
            }
        }
    };

    /*
    |--------------------------------------------------------------------------
    | LOOP FILE
    |--------------------------------------------------------------------------
    */

    foreach ($request->file('files') as $file) {

        $path = $file->getRealPath();

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $sourceFile = $file->getClientOriginalName();

        /*
        |--------------------------------------------------------------------------
        | EXCEL
        |--------------------------------------------------------------------------
        */

        if (in_array($extension, ['xls', 'xlsx'])) {

            $spreadsheet = IOFactory::load($path);

            foreach (
                $spreadsheet->getWorksheetIterator()
                as $sheet
            ) {

                $sheetName = trim(
                    $sheet->getTitle()
                );

                /*
                |--------------------------------------------------------------------------
                | SKIP SHEET TIDAK DIPAKAI
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        $sheetName,
                        $allowedSheets
                    )
                ) {
                    continue;
                }

                $rows = $sheet->toArray(
                    null,
                    true,
                    true,
                    false
                );

                $processRows(
                    $rows,
                    $sourceFile
                );
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | CSV / TXT
            |--------------------------------------------------------------------------
            */

            $content = file($path);

            if (
                !$content ||
                count($content) === 0
            ) {
                continue;
            }

            $delimiter = ",";

            $firstLine = $content[0];

            if (
                substr_count($firstLine, ";")
                >
                substr_count($firstLine, ",")
            ) {
                $delimiter = ";";
            }

            $handle = fopen($path, 'r');

            if (!$handle) {
                continue;
            }

            $rows = [];

            while (
                ($row = fgetcsv(
                    $handle,
                    10000,
                    $delimiter
                )) !== false
            ) {

                $rows[] = $row;
            }

            fclose($handle);

            $processRows(
                $rows,
                $sourceFile
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FLUSH SISA BUFFER
    |--------------------------------------------------------------------------
    */

    $flushBuffer();

    return back()->with(
        'success',
        "Import berhasil! Imported: {$totalImported}, Skipped: {$totalSkipped}"
    );
}

    public function data(Request $request)
{
    $query = Swdkllj::query();

    /*
    |--------------------------------------------------------------------------
    | MAPPING KMP RESMI
    |--------------------------------------------------------------------------
    */

    $zonaMapping = [

        'SAMSAT I' => [
            'SEMBUNGHARJO',
            'TLOGOMULYO',
            'BUGANGAN',
            'PEDURUNGAN TENGAH',
            'PENDRIKAN LOR',
            'PANGGUNG KIDUL',
            'GAYAMSARI'
        ],

        'SAMSAT II' => [
            'SENDANGGUWO',
            'LAMPER TENGAH',
            'SAMPANGAN',
            'BENDAN NGISOR',
            'SAMBIROTO',
            'JATINGALEH',
            'MANGUNHARJO',
            'GEDAWANG'
        ],

        'SAMSAT III' => [
            'WONOSARI',
            'GISIKDRONO',
            'TUGU',
            'MANYARAN',
            'KALIBANTENG KULON',
            'KEMBANGARUM',
            'KALIBANTENG KIDUL',
            'KANDRI',
            'SEKARAN',
            'MANGUNSARI',
            'CEPOKO',
            'NONGKO SAWIT'
        ]
    ];

    /*
    |--------------------------------------------------------------------------
    | FILTER ZONA
    |--------------------------------------------------------------------------
    */

    $zona = strtoupper(trim($request->zona ?? ''));

    if (!empty($zona) && isset($zonaMapping[$zona])) {

        $query->where(function ($q) use ($zonaMapping, $zona) {

            foreach ($zonaMapping[$zona] as $wilayah) {

                $q->orWhereRaw(
                    "UPPER(alamat) LIKE ?",
                    ['%' . strtoupper($wilayah) . '%']
                );
            }
        });
    }

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

    $statusDipilih = strtoupper(
        trim($request->status ?? '')
    );

    if ($statusDipilih === 'LUNAS') {

        $query->whereRaw(
            "UPPER(TRIM(status_bayar)) = 'LUNAS'"
        );

    } elseif ($statusDipilih === 'BELUM') {

        $query->where(function ($q) {

            $q->whereNull('status_bayar')
                ->orWhere('status_bayar', '')
                ->orWhereRaw(
                    "UPPER(TRIM(status_bayar)) = 'BELUM'"
                )
                ->orWhereRaw(
                    "UPPER(TRIM(status_bayar)) = 'BELUM BAYAR'"
                );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE DATA
    |--------------------------------------------------------------------------
    */

    $data = (clone $query)
        ->latest()
        ->paginate(50)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | DEFAULT VALUE
    |--------------------------------------------------------------------------
    */

    $online = 0;
    $konvensional = 0;

    $nominalOnline = 0;
    $nominalKonvensional = 0;

    $persenOnline = 0;
    $persenKonvensional = 0;

    $kmpResults = [];
    $topKmp = [];

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD LUNAS
    |--------------------------------------------------------------------------
    */

    if ($statusDipilih === 'LUNAS') {

        /*
        |--------------------------------------------------------------------------
        | DONUT CHART
        |--------------------------------------------------------------------------
        */

        $online = (clone $query)
            ->whereRaw("
                LOWER(TRIM(metode_pembayaran))
                = 'online'
            ")
            ->count();

        $konvensional = (clone $query)
            ->whereRaw("
                LOWER(TRIM(metode_pembayaran))
                = 'konvensional'
            ")
            ->count();

        /*
        |--------------------------------------------------------------------------
        | NOMINAL
        |--------------------------------------------------------------------------
        */

        $nominalOnline = (clone $query)
            ->whereRaw("
                LOWER(TRIM(metode_pembayaran))
                = 'online'
            ")
            ->sum('nominal_bayar');

        $nominalKonvensional = (clone $query)
            ->whereRaw("
                LOWER(TRIM(metode_pembayaran))
                = 'konvensional'
            ")
            ->sum('nominal_bayar');

        $totalNominal =
            $nominalOnline +
            $nominalKonvensional;

        $persenOnline =
            $totalNominal > 0
            ? round(
                ($nominalOnline / $totalNominal) * 100,
                1
            )
            : 0;

        $persenKonvensional =
            $totalNominal > 0
            ? round(
                ($nominalKonvensional / $totalNominal) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA ONLINE LUNAS
        |--------------------------------------------------------------------------
        */

        $allOnline = (clone $query)

            ->whereRaw("
                LOWER(TRIM(metode_pembayaran))
                = 'online'
            ")

            ->select([
                'id_petugas',
                'nama_ric',
                'nopol',
                'nama_wp',
                'alamat',
                'nominal_bayar'
            ])

            ->get();

        /*
        |--------------------------------------------------------------------------
        | PARSER KELURAHAN = KMP
        |--------------------------------------------------------------------------
        */

        foreach ($zonaMapping as $namaZona => $wilayahList) {

            foreach ($wilayahList as $wilayah) {

                $detailOnline = $allOnline
                    ->filter(function ($item) use ($wilayah) {

                        $alamat = strtoupper(
                            trim($item->alamat ?? '')
                        );

                        $match = false;

                        /*
                        |--------------------------------------------------------------------------
                        | FORMAT KEL.
                        |--------------------------------------------------------------------------
                        */

                        if (preg_match(
                            '/KEL\.?\s+' .
                            preg_quote($wilayah, '/') .
                            '\b/i',
                            $alamat
                        )) {
                            $match = true;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | FORMAT RT/RW
                        |--------------------------------------------------------------------------
                        */

                        if (!$match) {

                            if (preg_match(
                                '/^' .
                                preg_quote($wilayah, '/') .
                                '\s+RT/i',
                                $alamat
                            )) {

                                $match = true;
                            }
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | FALLBACK
                        |--------------------------------------------------------------------------
                        */

                        if (!$match) {

                            if (str_contains(
                                $alamat,
                                strtoupper($wilayah)
                            )) {

                                $match = true;
                            }
                        }

                        return $match;
                    })
                    ->values();

                $kmpResults[$namaZona][] = [

                    'nama' => $wilayah,

                    'total' => $detailOnline->count(),

                    'list' => $detailOnline

                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TOP 3 KMP
        |--------------------------------------------------------------------------
        */

        foreach ($kmpResults as $zonaKey => $items) {

           /*
|--------------------------------------------------------------------------
| TOP 3 GLOBAL KMP
|--------------------------------------------------------------------------
*/

$topKmp = collect($kmpResults)

    ->flatten(1)

    ->filter(function ($item) {
        return $item['total'] > 0;
    })

    ->sortByDesc('total')

    ->take(3)

    ->values()

    ->toArray();
        }
    }

    return view('data.index', compact(
        'data',
        'statusDipilih',
        'online',
        'konvensional',
        'nominalOnline',
        'nominalKonvensional',
        'persenOnline',
        'persenKonvensional',
        'kmpResults',
        'topKmp',
        'zona'
    ));
}
    public function visualisasi()
{
    /*
|--------------------------------------------------------------------------
| VISUAL STATUS KEPEMILIKAN
|--------------------------------------------------------------------------
*/

$kepemilikanChart = Swdkllj::selectRaw("
        TRIM(status_kepemilikan) as status,
        COUNT(*) as total
    ")
    ->whereNotNull('status_kepemilikan')
    ->whereRaw("TRIM(status_kepemilikan) != ''")
    ->groupBy('status')
    ->orderByDesc('total')
    ->get();


    /*
    |--------------------------------------------------------------------------
    | GOLONGAN SUDAH BAYAR
    |--------------------------------------------------------------------------
    */

    $dpCount = Swdkllj::whereRaw("
            UPPER(TRIM(status_bayar)) = 'LUNAS'
        ")
        ->whereRaw("
            UPPER(TRIM(gol)) LIKE '%DP%'
        ")
        ->count();

    $ciCount = Swdkllj::whereRaw("
            UPPER(TRIM(status_bayar)) = 'LUNAS'
        ")
        ->whereRaw("
            UPPER(TRIM(gol)) LIKE '%C%'
        ")
        ->count();

    /*
    |--------------------------------------------------------------------------
    | BELUM BAYAR
    |--------------------------------------------------------------------------
    */

    $belumDP = Swdkllj::where(function ($q) {

            $q->whereNull('status_bayar')
              ->orWhereRaw("TRIM(status_bayar) = ''")
              ->orWhereRaw("UPPER(TRIM(status_bayar)) = 'BELUM'")
              ->orWhereRaw("UPPER(TRIM(status_bayar)) = 'BELUM BAYAR'");

        })
        ->whereRaw("
            UPPER(TRIM(gol)) LIKE '%DP%'
        ")
        ->count();

    $belumCI = Swdkllj::where(function ($q) {

            $q->whereNull('status_bayar')
              ->orWhereRaw("TRIM(status_bayar) = ''")
              ->orWhereRaw("UPPER(TRIM(status_bayar)) = 'BELUM'")
              ->orWhereRaw("UPPER(TRIM(status_bayar)) = 'BELUM BAYAR'");

        })
        ->whereRaw("
            UPPER(TRIM(gol)) LIKE '%C%'
        ")
        ->count();

    /*
    |--------------------------------------------------------------------------
    | NOMINAL LUNAS
    |--------------------------------------------------------------------------
    */

    $totalDPLunas = $dpCount * 140000;
    $totalCILunas = $ciCount * 32000;

    $totalNominalLunas =
        $totalDPLunas +
        $totalCILunas;

    /*
    |--------------------------------------------------------------------------
    | NOMINAL BELUM BAYAR
    |--------------------------------------------------------------------------
    */

    $totalDPBelum = $belumDP * 140000;
    $totalCIBelum = $belumCI * 32000;

    $totalNominalBelum =
        $totalDPBelum +
        $totalCIBelum;

    /*
|--------------------------------------------------------------------------
| PERSENTASE PEMBAYARAN
|--------------------------------------------------------------------------
*/

$totalKeseluruhanNominal =
    $totalNominalLunas +
    $totalNominalBelum;

$persenLunas =
    $totalKeseluruhanNominal > 0
    ? round(
        ($totalNominalLunas / $totalKeseluruhanNominal) * 100,
        1
    )
    : 0;

$persenBelum =
    $totalKeseluruhanNominal > 0
    ? round(
        ($totalNominalBelum / $totalKeseluruhanNominal) * 100,
        1
    )
    : 0;

    /*
    |--------------------------------------------------------------------------
    | TOTAL PENDAPATAN
    | LUNAS - BELUM
    |--------------------------------------------------------------------------
    */

    $grandTotal =
        $totalNominalLunas
        -
        $totalNominalBelum;

    /*
    |--------------------------------------------------------------------------
    | TOTAL JIKA SEMUA BAYAR
    |--------------------------------------------------------------------------
    */

    $totalSemuaBayar =
        (($dpCount + $belumDP) * 140000)
        +
        (($ciCount + $belumCI) * 32000);

    /*
    |--------------------------------------------------------------------------
    | TOTAL KENDARAAN
    |--------------------------------------------------------------------------
    */

    $totalLunas =
        $dpCount + $ciCount;

    $totalBelum =
        $belumDP + $belumCI;

    /*
    |--------------------------------------------------------------------------
    | TOP PETUGAS
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| PROGRESS PETUGAS BERDASARKAN STATUS PENYERAHAN
|--------------------------------------------------------------------------
*/

$petugas = Swdkllj::selectRaw("
    nama_ric,

    COUNT(*) as total,

    SUM(
        CASE
            WHEN status_penyerahan IS NOT NULL
                 AND TRIM(status_penyerahan) != ''
            THEN 1
            ELSE 0
        END
    ) as selesai
")
->whereNotNull('nama_ric')
->groupBy('nama_ric')
->orderByDesc('selesai')
->limit(15)
->get()
->map(function ($item) {

    $item->progress =
        $item->total > 0
        ? round(($item->selesai / $item->total) * 100)
        : 0;

    return $item;
});

    /*
    |--------------------------------------------------------------------------
    | SOURCE FILE
    |--------------------------------------------------------------------------
    */

    $sourceFiles = Swdkllj::selectRaw(
            'source_file, COUNT(*) as total'
        )
        ->groupBy('source_file')
        ->orderByDesc('total')
        ->limit(10)
        ->get();

    return view('visualisasi.index', compact(
        'dpCount',
        'ciCount',
        'belumDP',
        'belumCI',
        'totalLunas',
        'totalBelum',
        'totalDPLunas',
        'totalCILunas',
        'totalDPBelum',
        'totalCIBelum',
        'totalNominalLunas',
        'totalNominalBelum',
        'grandTotal',
        'totalSemuaBayar',
        'petugas',
        'kepemilikanChart',
        'persenLunas',
        'persenBelum',
        'totalNominalLunas',
        'totalNominalBelum',
        'sourceFiles'
    ));
}


public function laporan(Request $request)
{
    $query = Swdkllj::query();

    /*
    |--------------------------------------------------------------------------
    | FILTER PETUGAS
    |--------------------------------------------------------------------------
    */

    if ($request->filled('petugas')) {

    $petugas = trim(
        $request->petugas
    );

    $query->whereRaw(
        "TRIM(UPPER(nama_ric))
        = TRIM(UPPER(?))",
        [$petugas]
    );
}

    /*
    |--------------------------------------------------------------------------
    | REKAP PETUGAS
    |--------------------------------------------------------------------------
    | 1 PETUGAS = 1 ROW
    |--------------------------------------------------------------------------
    */

    $laporanPetugas =
        (clone $query)

        /*
        |--------------------------------------------------------------------------
        | IDENTITAS PETUGAS
        |--------------------------------------------------------------------------
        */

        ->selectRaw("
            TRIM(nama_ric)
            as nama_ric
        ")

        ->selectRaw("
            MAX(id_petugas)
            as id_petugas
        ")

        /*
        |--------------------------------------------------------------------------
        | TOTAL TARGET
        |--------------------------------------------------------------------------
        */

        ->selectRaw("
            COUNT(*) as total_target
        ")

        /*
        |--------------------------------------------------------------------------
        | GOLONGAN
        | DP = MOBIL
        | CI = MOTOR
        |--------------------------------------------------------------------------
        */

        ->selectRaw("
            SUM(
                CASE
                    WHEN UPPER(
                        TRIM(gol)
                    ) LIKE '%DP%'
                    THEN 1
                    ELSE 0
                END
            ) as total_dp
        ")

        ->selectRaw("
            SUM(
                CASE
                    WHEN UPPER(
                        TRIM(gol)
                    ) LIKE '%C%'
                    THEN 1
                    ELSE 0
                END
            ) as total_ci
        ")

        /*
        |--------------------------------------------------------------------------
        | STATUS PENYERAHAN
        |--------------------------------------------------------------------------
        */

        ->selectRaw("
            SUM(
                CASE
                    WHEN status_penyerahan
                    IS NOT NULL
                    AND TRIM(
                        status_penyerahan
                    ) != ''
                    THEN 1
                    ELSE 0
                END
            ) as total_penyerahan
        ")

        /*
        |--------------------------------------------------------------------------
        | STATUS KEPEMILIKAN
        |--------------------------------------------------------------------------
        */

        ->selectRaw("
            SUM(
                CASE
                    WHEN status_kepemilikan
                    IS NOT NULL
                    AND TRIM(
                        status_kepemilikan
                    ) != ''
                    THEN 1
                    ELSE 0
                END
            ) as total_kepemilikan
        ")

        /*
        |--------------------------------------------------------------------------
        | STATUS BAYAR
        |--------------------------------------------------------------------------
        */

        ->selectRaw("
            SUM(
                CASE
                    WHEN UPPER(
                        TRIM(status_bayar)
                    ) = 'LUNAS'
                    THEN 1
                    ELSE 0
                END
            ) as lunas
        ")

        ->selectRaw("
            SUM(
                CASE
                    WHEN status_bayar IS NULL
                    OR TRIM(
                        status_bayar
                    ) = ''
                    OR UPPER(
                        TRIM(status_bayar)
                    ) = 'BELUM'
                    OR UPPER(
                        TRIM(status_bayar)
                    ) = 'BELUM BAYAR'
                    THEN 1
                    ELSE 0
                END
            ) as belum
        ")

        /*
        |--------------------------------------------------------------------------
        | NOMINAL BAYAR
        |--------------------------------------------------------------------------
        */

        ->selectRaw("
            COALESCE(
                SUM(nominal_bayar),
                0
            ) as total_nominal
        ")

        /*
        |--------------------------------------------------------------------------
        | METODE PEMBAYARAN
        | HANYA YANG LUNAS
        |--------------------------------------------------------------------------
        */

        ->selectRaw("
            SUM(
                CASE
                    WHEN
                        UPPER(
                            TRIM(status_bayar)
                        ) = 'LUNAS'

                    AND

                        LOWER(
                            TRIM(
                                metode_pembayaran
                            )
                        ) = 'online'

                    THEN 1
                    ELSE 0
                END
            ) as online
        ")

        ->selectRaw("
            SUM(
                CASE
                    WHEN
                        UPPER(
                            TRIM(status_bayar)
                        ) = 'LUNAS'

                    AND

                        LOWER(
                            TRIM(
                                metode_pembayaran
                            )
                        ) = 'konvensional'

                    THEN 1
                    ELSE 0
                END
            ) as konvensional
        ")

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PETUGAS
        |--------------------------------------------------------------------------
        */

        ->whereNotNull('nama_ric')

        ->whereRaw("
            TRIM(nama_ric) != ''
        ")

        /*
        |--------------------------------------------------------------------------
        | GROUP PETUGAS
        |--------------------------------------------------------------------------
        */

        ->groupByRaw("
            TRIM(nama_ric)
        ")

        ->orderBy('nama_ric')

        ->get()

        /*
        |--------------------------------------------------------------------------
        | ATTRIBUTE TAMBAHAN
        |--------------------------------------------------------------------------
        */

        ->map(function ($item) {

            /*
            |--------------------------------------------------------------------------
            | BELUM PENYERAHAN
            |--------------------------------------------------------------------------
            */

            $item->belum_penyerahan =
                $item->total_target
                -
                $item->total_penyerahan;

            /*
            |--------------------------------------------------------------------------
            | BELUM KEPEMILIKAN
            |--------------------------------------------------------------------------
            */

            $item->belum_kepemilikan =
                $item->total_target
                -
                $item->total_kepemilikan;

            /*
            |--------------------------------------------------------------------------
            | PROGRESS PENYERAHAN
            |--------------------------------------------------------------------------
            */

            $item->persen_penyerahan =
                $item->total_target > 0
                ? round(
                    (
                        $item->total_penyerahan
                        /
                        $item->total_target
                    ) * 100,
                    1
                )
                : 0;

            /*
            |--------------------------------------------------------------------------
            | PROGRESS PEMBAYARAN
            |--------------------------------------------------------------------------
            */

            $item->persen_lunas =
                $item->total_target > 0
                ? round(
                    (
                        $item->lunas
                        /
                        $item->total_target
                    ) * 100,
                    1
                )
                : 0;

            return $item;
        });

    /*
    |--------------------------------------------------------------------------
    | LIST PETUGAS FILTER
    |--------------------------------------------------------------------------
    */

    $petugasList = Swdkllj::selectRaw("
            DISTINCT TRIM(nama_ric)
            as nama_ric
        ")
        ->whereNotNull('nama_ric')
        ->whereRaw("
            TRIM(nama_ric) != ''
        ")
        ->orderBy('nama_ric')
        ->pluck('nama_ric');

    /*
    |--------------------------------------------------------------------------
    | SUMMARY GLOBAL
    |--------------------------------------------------------------------------
    */

    $totalPetugas =
        $laporanPetugas->count();

    $totalTarget =
        $laporanPetugas
            ->sum('total_target');

    $totalLunas =
        $laporanPetugas
            ->sum('lunas');

    $totalBelum =
        $laporanPetugas
            ->sum('belum');

    $grandNominal =
        $laporanPetugas
            ->sum('total_nominal');

    return view(
        'laporan.index',
        compact(
            'laporanPetugas',
            'petugasList',

            'totalPetugas',
            'totalTarget',
            'totalLunas',
            'totalBelum',
            'grandNominal'
        )
    );
}


public function laporanPetugas($namaRic)
{
    /*
    |--------------------------------------------------------------------------
    | IDENTITAS PETUGAS
    |--------------------------------------------------------------------------
    */

    $petugas = Swdkllj::whereRaw(
        "TRIM(UPPER(nama_ric))
        = TRIM(UPPER(?))",
        [$namaRic]
    )->first();

    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA DATA PETUGAS
    |--------------------------------------------------------------------------
    */

    $items = Swdkllj::whereRaw(
        "TRIM(UPPER(nama_ric))
        = TRIM(UPPER(?))",
        [$namaRic]
    )->get();

    /*
    |--------------------------------------------------------------------------
    | TOTAL TARGET
    |--------------------------------------------------------------------------
    */

    $totalTarget = $items->count();

    /*
    |--------------------------------------------------------------------------
    | A. STATUS PENYERAHAN
    |--------------------------------------------------------------------------
    */

    $statusPenyerahan = $items
        ->groupBy(function ($item) {

            return strtoupper(
                trim(
                    $item->status_penyerahan
                    ?? 'TIDAK ADA STATUS'
                )
            );
        })
        ->map(function ($group) {

            return $group->count();
        })
        ->sortDesc();

    $totalPenyerahan =
        $statusPenyerahan->sum();

    /*
    |--------------------------------------------------------------------------
    | B. STATUS KEPEMILIKAN
    |--------------------------------------------------------------------------
    */

    $statusKepemilikan = $items
        ->groupBy(function ($item) {

            return strtoupper(
                trim(
                    $item->status_kepemilikan
                    ?? 'TIDAK ADA STATUS'
                )
            );
        })
        ->map(function ($group) {

            return $group->count();
        })
        ->sortDesc();

    /*
    |--------------------------------------------------------------------------
    | C. STATUS PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    $statusBayar = $items
        ->groupBy(function ($item) {

            $status = strtoupper(
                trim(
                    $item->status_bayar
                    ?? ''
                )
            );

            if (
                empty($status)
            ) {
                return 'BELUM BAYAR';
            }

            return $status;
        })
        ->map(function ($group) {

            return $group->count();
        })
        ->sortDesc();

    /*
    |--------------------------------------------------------------------------
    | NOMINAL BAYAR
    |--------------------------------------------------------------------------
    */

    $totalNominalBayar =
        $items->sum(
            'nominal_bayar'
        );

    /*
    |--------------------------------------------------------------------------
    | DATA REKAP
    |--------------------------------------------------------------------------
    */

    $laporan = [

        'namaRic' =>
            $namaRic,

        'idPetugas' =>
            $petugas->id_petugas
            ?? '-',

        'totalTarget' =>
            $totalTarget,

        'statusPenyerahan' =>
            $statusPenyerahan,

        'totalPenyerahan' =>
            $totalPenyerahan,

        'statusKepemilikan' =>
            $statusKepemilikan,

        'statusBayar' =>
            $statusBayar,

        'nominalBayar' =>
            $totalNominalBayar,
    ];

    return view(
        'laporan.petugas',
        compact(
            'laporan',
            'petugas',
            'namaRic'
        )
    );
}

}