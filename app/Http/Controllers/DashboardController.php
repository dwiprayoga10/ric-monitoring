<?php

namespace App\Http\Controllers;

use App\Models\Swdkllj;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\DB;
use App\Models\AppSetting;

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

        $sudah_bayar = Swdkllj::whereRaw(
    'UPPER(TRIM(status_bayar)) = ?',
    ['SUDAH BAYAR']
)->count();

/*
|--------------------------------------------------------------------------
| TIDAK DIPAKAI LAGI
|--------------------------------------------------------------------------
*/

$dp = 0;

        $belumBayar = Swdkllj::where(function ($q) {

            $q->whereNull('status_bayar')
              ->orWhereRaw('TRIM(status_bayar) = ""')
              ->orWhereRaw('UPPER(TRIM(status_bayar)) = "BELUM"')
              ->orWhereRaw('UPPER(TRIM(status_bayar)) = "BELUM BAYAR"');

        })->count();

        $lastUpdate =
    AppSetting::where(
        'key',
        'last_update'
    )->first();

$lastUpdate =
    $lastUpdate
    ? json_decode(
        $lastUpdate->value,
        true
    )
    : null;

        return view('dashboard.index', compact(
            'data',
            'totalData',
            'sudah_bayar',
            'dp',
            'belumBayar',
            'lastUpdate'
        ));
    }


// public function syncSpreadsheet()
// {
//     set_time_limit(0);
//     ini_set('memory_limit', '1024M');

//     $spreadsheetId =
//         '1ueG5KDL_gguPCEg5BA89IcQc9Yot-hoqEyu-GFdhWiM';

//     /*
//     |--------------------------------------------------------------------------
//     | SHEET YANG DIPROSES
//     |--------------------------------------------------------------------------
//     */

//     $allowedSheets = [

//         [
//             'name' => 'CEK DATA I fix',
//             'gid' => '753601129'
//         ],

//         [
//             'name' => 'CEK DATA II fix',
//             'gid' => '59744365'
//         ],

//         [
//             'name' => 'CEK DATA III FIX',
//             'gid' => '1861244810'
//         ]
//     ];

//     /*
//     |--------------------------------------------------------------------------
//     | PREPARE
//     |--------------------------------------------------------------------------
//     */

//     $buffer = [];
//     $uniqueData = [];
//     $sheetTotals = [];

//     /*
//     |--------------------------------------------------------------------------
//     | LOOP SEMUA SHEET
//     |--------------------------------------------------------------------------
//     */

//     foreach ($allowedSheets as $sheet) {

//         $beforeCount = count($buffer);

//         $url =
//             "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv&gid="
//             . $sheet['gid'];

//         $csv = @file_get_contents($url);

//         if (!$csv) {

//             $sheetTotals[
//                 $sheet['name']
//             ] = 'FAILED';

//             continue;
//         }

//         $rows = array_map(
//             'str_getcsv',
//             explode("\n", $csv)
//         );

//         $headerFound = false;

//         foreach ($rows as $row) {

//             /*
//             |--------------------------------------------------------------------------
//             | RAPIIKAN DATA
//             |--------------------------------------------------------------------------
//             */

//             $row = array_map(function ($value) {
//                 return trim((string) ($value ?? ''));
//             }, $row);

//             /*
//             |--------------------------------------------------------------------------
//             | SKIP BARIS KOSONG
//             |--------------------------------------------------------------------------
//             */

//             if (
//                 count(array_filter($row))
//                 === 0
//             ) {
//                 continue;
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | DETECT HEADER
//             |--------------------------------------------------------------------------
//             */

//             if (!$headerFound) {

//                 $isHeader =
//                     strtoupper($row[0] ?? '') === 'NO'
//                     &&
//                     strtoupper($row[1] ?? '') === 'ID PETUGAS'
//                     &&
//                     strtoupper($row[2] ?? '') === 'NAMA RIC'
//                     &&
//                     strtoupper($row[3] ?? '') === 'NOPOL'
//                     &&
//                     strtoupper($row[4] ?? '') === 'NAMA WP';

//                 if ($isHeader) {
//                     $headerFound = true;
//                 }

//                 continue;
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | SKIP HEADER NYANGKUT
//             |--------------------------------------------------------------------------
//             */

//             if (
//                 strtoupper($row[0] ?? '') === 'NO'
//                 ||
//                 strtoupper($row[3] ?? '') === 'NOPOL'
//             ) {
//                 continue;
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | VALIDASI WAJIB
//             |--------------------------------------------------------------------------
//             */

//             $idPetugas =
//                 trim($row[1] ?? '');

//             $nopol = strtoupper(
//                 preg_replace(
//                     '/[^A-Z0-9]/',
//                     '',
//                     trim($row[3] ?? '')
//                 )
//             );

//             $namaWp =
//                 trim($row[4] ?? '');

//             if (
//                 empty($idPetugas)
//                 ||
//                 empty($nopol)
//                 ||
//                 empty($namaWp)
//             ) {
//                 continue;
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | REMOVE DUPLICATE
//             | ONLY NOPOL
//             |--------------------------------------------------------------------------
//             */

//             /*
// |--------------------------------------------------------------------------
// | REMOVE DUPLICATE
// | ONLY NOPOL
// |--------------------------------------------------------------------------
// */

// /*
// |--------------------------------------------------------------------------
// | REMOVE DUPLICATE
// |--------------------------------------------------------------------------
// */

// $key =
//     strtoupper($nopol)
//     . '_'
//     . strtoupper($idPetugas);

// if (isset($uniqueData[$key])) {

//     continue;
// }

// $uniqueData[$key] = true;

//             /*
//             |--------------------------------------------------------------------------
//             | FORMAT MASA BERLAKU
//             |--------------------------------------------------------------------------
//             */

//             $masaBerlaku = null;

//             try {

//                 $rawTanggal =
//                     trim($row[6] ?? '');

//                 if (!empty($rawTanggal)) {

//                     $bulan = [
//                         'Januari' => 'January',
//                         'Februari' => 'February',
//                         'Maret' => 'March',
//                         'April' => 'April',
//                         'Mei' => 'May',
//                         'Juni' => 'June',
//                         'Juli' => 'July',
//                         'Agustus' => 'August',
//                         'September' => 'September',
//                         'Oktober' => 'October',
//                         'November' => 'November',
//                         'Desember' => 'December',
//                     ];

//                     $rawTanggal =
//                         str_replace(
//                             array_keys($bulan),
//                             array_values($bulan),
//                             $rawTanggal
//                         );

//                     $masaBerlaku =
//                         \Carbon\Carbon::createFromFormat(
//                             'd F Y',
//                             $rawTanggal
//                         )->format('Y-m-d');
//                 }

//             } catch (\Throwable $e) {

//                 $masaBerlaku = null;
//             }

//             /*
// |--------------------------------------------------------------------------
// | STATUS BAYAR
// |--------------------------------------------------------------------------
// */

// $statusBayar = strtoupper(
//     trim($row[11] ?? '')
// );

// /*
// |--------------------------------------------------------------------------
// | NORMALISASI STATUS
// | Spreadsheet:
// | - SUDAH BAYAR
// | - BELUM BAYAR
// |--------------------------------------------------------------------------
// */

// if (
//     $statusBayar === 'SUDAH BAYAR'
//     ||
//     $statusBayar === 'LUNAS'
// ) {

//     $statusBayar =
//         'SUDAH BAYAR';

// } else {

//     $statusBayar =
//         'BELUM BAYAR';
// }

//             /*
//             |--------------------------------------------------------------------------
//             | NOMINAL BAYAR
//             |--------------------------------------------------------------------------
//             */

//             $nominalBayar = null;

//             if (!empty($row[12])) {

//                 $cleanNominal =
//                     preg_replace(
//                         '/[^0-9]/',
//                         '',
//                         $row[12]
//                     );

//                 $nominalBayar =
//                     is_numeric(
//                         $cleanNominal
//                     )
//                     ? (float)
//                         $cleanNominal
//                     : null;
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | METODE PEMBAYARAN
//             |--------------------------------------------------------------------------
//             */

//             $metodePembayaran =
//                 strtolower(
//                     trim(
//                         $row[13] ?? ''
//                     )
//                 );

//             $metodePembayaran =
//                 str_replace(
//                     ' ',
//                     '',
//                     $metodePembayaran
//                 );

//             if (
//                 $metodePembayaran
//                 === 'online'
//             ) {

//                 $metodePembayaran =
//                     'online';

//             } else {

//                 $metodePembayaran =
//                     'konvensional';
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | BUFFER INSERT
//             |--------------------------------------------------------------------------
//             */

//             $buffer[] = [

//                 'no' =>
//                     is_numeric(
//                         $row[0] ?? null
//                     )
//                     ? (int) $row[0]
//                     : null,

//                 'id_petugas' =>
//                     $idPetugas,

//                 'nama_ric' =>
//                     $row[2] ?? null,

//                 'nopol' =>
//                     $nopol,

//                 'nama_wp' =>
//                     $namaWp,

//                 'alamat' =>
//                     $row[5] ?? null,

//                 'masa_berlaku' =>
//                     $masaBerlaku,

//                 'gol' =>
//                     $row[7] ?? null,

//                 'no_hp' =>
//                     $row[8] ?? null,

//                 'status_penyerahan' =>
//                     $row[9] ?? null,

//                 'status_kepemilikan' =>
//                     $row[10] ?? null,

//                 'status_bayar' =>
//                     $statusBayar,

//                 'nominal_bayar' =>
//                     $nominalBayar,

//                 'metode_pembayaran' =>
//                     $metodePembayaran,

//                 'source_file' =>
//                     $sheet['name'],

//                 'created_at' =>
//                     now(),

//                 'updated_at' =>
//                     now(),
//             ];
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | TOTAL PER SHEET
//         |--------------------------------------------------------------------------
//         */

//         $afterCount = count($buffer);

//         $sheetTotals[
//             $sheet['name']
//         ] =
//             $afterCount
//             -
//             $beforeCount;
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | INSERT DATABASE
//     |--------------------------------------------------------------------------
//     */

//     /*
// |--------------------------------------------------------------------------
// | INSERT DATABASE
// |--------------------------------------------------------------------------
// */

// if (!empty($buffer)) {

//     try {

//         Swdkllj::query()->delete();

//         Swdkllj::insert($buffer);

//     } catch (\Throwable $e) {

//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }

//     /*
//     |--------------------------------------------------------------------------
//     | RESPONSE
//     |--------------------------------------------------------------------------
//     */

//     /*
// |--------------------------------------------------------------------------
// | LAST UPDATE
// |--------------------------------------------------------------------------
// */

// AppSetting::updateOrCreate(
//     [
//         'key' =>
//             'last_update'
//     ],
//     [
//         'value' =>
//             json_encode([
//                 'time' => now(),
//                 'source' =>
//                     'Spreadsheet Sync'
//             ])
//     ]
// );

//     return response()->json([
//         'success' => true,
//         'message' => 'Sync berhasil',
//         'total' => count($buffer),
//         'per_sheet' => $sheetTotals,
//     ]);
// }

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
    | FILTER METODE PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    $metodeDipilih = strtolower(
        trim($request->metode ?? '')
    );

    if ($metodeDipilih === 'online') {

        $query->whereRaw(
            "LOWER(TRIM(metode_pembayaran)) = 'online'"
        );

    } elseif ($metodeDipilih === 'konvensional') {

        $query->whereRaw(
            "LOWER(TRIM(metode_pembayaran)) = 'konvensional'"
        );
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

    if ($statusDipilih === 'SUDAH BAYAR'){

        $query->whereRaw(
            "UPPER(TRIM(status_bayar)) = 'SUDAH BAYAR'"
        );

    } elseif (
    $statusDipilih === 'BELUM'
    ||
    $statusDipilih === 'BELUM BAYAR'
) {

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

    if ($statusDipilih === 'SUDAH BAYAR'){

        /*
        |--------------------------------------------------------------------------
        | DONUT CHART
        |--------------------------------------------------------------------------
        */

        $onlineData = (clone $query)

    ->whereRaw("
        LOWER(TRIM(metode_pembayaran))
        = 'online'
    ")

    ->get();

    $online = $onlineData->count();

       $konvensionalData = (clone $query)

    ->whereRaw("
        LOWER(TRIM(metode_pembayaran))
        = 'konvensional'
    ")

    ->get();

    $konvensional = $konvensionalData->count();
        /*Q
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

        /*
        |--------------------------------------------------------------------------
        | PERSENTASE BERDASARKAN JUMLAH METODE
        |--------------------------------------------------------------------------
        */

        $totalMetode =
            $online + $konvensional;

        $persenOnline =
            $totalMetode > 0
            ? round(
                ($online / $totalMetode) * 100,
                1
            )
            : 0;

        $persenKonvensional =
            $totalMetode > 0
            ? round(
                ($konvensional / $totalMetode) * 100,
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

                        if (preg_match(
                            '/KEL\.?\s+' .
                            preg_quote($wilayah, '/') .
                            '\b/i',
                            $alamat
                        )) {
                            $match = true;
                        }

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
        'metodeDipilih'
    ));
}
    public function visualisasi()
{
    /*
    |--------------------------------------------------------------------------
    | STATUS KEPEMILIKAN
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
    | GLOBAL STATUS PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    $sudahBayarItems = Swdkllj::whereRaw("
        UPPER(TRIM(status_bayar))
        = 'SUDAH BAYAR'
    ")->get();

    $belumBayarItems = Swdkllj::where(function ($q) {

        $q->whereNull('status_bayar')
            ->orWhereRaw("TRIM(status_bayar) = ''")
            ->orWhereRaw("UPPER(TRIM(status_bayar)) = 'BELUM'")
            ->orWhereRaw("UPPER(TRIM(status_bayar)) = 'BELUM BAYAR'");

    })->get();

    /*
    |--------------------------------------------------------------------------
    | SUDAH BAYAR
    |--------------------------------------------------------------------------
    */

    $dpCount = $sudahBayarItems
    
    

        ->filter(function ($item) {

            return str_starts_with(
    strtoupper(trim($item->gol ?? '')),
    'DP'
);
        })

        ->count();

        $ciCount = $sudahBayarItems

    ->filter(function ($item) {

        $gol = strtoupper(
            trim($item->gol ?? '')
        );

        return str_starts_with(
            $gol,
            'C1'
        );
    })

    ->count();

    /*
    |--------------------------------------------------------------------------
    | BELUM BAYAR
    |--------------------------------------------------------------------------
    */

    $belumDP = $belumBayarItems

    ->filter(function ($item) {

        return str_starts_with(
            strtoupper(trim($item->gol ?? '')),
            'DP'
        );
    })

    ->count();

    $belumCI = $belumBayarItems

    ->filter(function ($item) {

        $gol = strtoupper(
            trim($item->gol ?? '')
        );

        return str_starts_with(
            $gol,
            'C1'
        );
    })

    ->count();

    /*
    |--------------------------------------------------------------------------
    | TOTAL KENDARAAN
    |--------------------------------------------------------------------------
    */

    $totalsudah_bayar =
        $sudahBayarItems->count();

    $totalBelum =
        $belumBayarItems->count();
        

    /*
|--------------------------------------------------------------------------
| NOMINAL SUDAH BAYAR
|--------------------------------------------------------------------------
*/

$totalNominalsudah_bayar =
    $sudahBayarItems->sum('nominal_bayar');

/*
|--------------------------------------------------------------------------
| NOMINAL BELUM BAYAR
|--------------------------------------------------------------------------
*/

$totalDPBelum =
    $belumDP * 140000;

$totalCIBelum =
    $belumCI * 32000;

$totalNominalBelum =
    $totalDPBelum + $totalCIBelum;
/*
|--------------------------------------------------------------------------
| TOTAL PER GOLONGAN
|--------------------------------------------------------------------------
*/

$totalDPsudah_bayar = $sudahBayarItems

    ->filter(function ($item) {

        return str_starts_with(
            strtoupper(trim($item->gol ?? '')),
            'DP'
        );
    })

    ->sum('nominal_bayar');

$totalCIsudah_bayar = $sudahBayarItems

    ->filter(function ($item) {

        return str_starts_with(
            strtoupper(trim($item->gol ?? '')),
            'C1'
        );
    })

    ->sum('nominal_bayar');

$totalDPBelum = $belumBayarItems

    ->filter(function ($item) {

        return str_starts_with(
            strtoupper(trim($item->gol ?? '')),
            'DP'
        );
    })

    ->sum('nominal_bayar');

$totalCIBelum = $belumBayarItems

    ->filter(function ($item) {

        return str_starts_with(
            strtoupper(trim($item->gol ?? '')),
            'C1'
        );
    })

    ->sum('nominal_bayar');

    /*
    |--------------------------------------------------------------------------
    | PERSENTASE PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    $totalKeseluruhanNominal =
        $totalNominalsudah_bayar +
        $totalNominalBelum;

    $persensudah_bayar =
        $totalKeseluruhanNominal > 0
        ? round(
            (
                $totalNominalsudah_bayar
                /
                $totalKeseluruhanNominal
            ) * 100,
            1
        )
        : 0;

    $persenBelum =
        $totalKeseluruhanNominal > 0
        ? round(
            (
                $totalNominalBelum
                /
                $totalKeseluruhanNominal
            ) * 100,
            1
        )
        : 0;

    /*
    |--------------------------------------------------------------------------
    | GAP TARGET PENDAPATAN
    |--------------------------------------------------------------------------
    */

    $grandTotal =
        $totalNominalsudah_bayar
        -
        $totalNominalBelum;

    /*
|--------------------------------------------------------------------------
| TOTAL JIKA SEMUA BAYAR
|--------------------------------------------------------------------------
*/

$totalSemuaBayar =
    $totalNominalsudah_bayar
    +
    $totalNominalBelum;
    /*
    |--------------------------------------------------------------------------
    | TOP PETUGAS
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
            ? round(
                (
                    $item->selesai
                    /
                    $item->total
                ) * 100
            )
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

    /*
|--------------------------------------------------------------------------
| AKUMULASI KERJA RIC
|--------------------------------------------------------------------------
*/

$totalKerjaRic =
    Swdkllj::whereNotNull(
        'status_kepemilikan'
    )
    ->whereRaw("
        TRIM(status_kepemilikan)
        != ''
    ")
    ->count();

$totalTargetRic =
    Swdkllj::count();

$persenKerjaRic =
    $totalTargetRic > 0
    ? round(
        (
            $totalKerjaRic
            /
            $totalTargetRic
        ) * 100,
        1
    )
    : 0;

    /*
|--------------------------------------------------------------------------
| INSIGHT RIC
|--------------------------------------------------------------------------
*/

$totalPetugasAktif =
    Swdkllj::whereNotNull(
        'status_kepemilikan'
    )
    ->distinct()
    ->count('nama_ric');

$statusDominan =
    collect(
        $kepemilikanChart
    )->sortByDesc('total')
    ->first();

$sisaPengerjaan =
    $totalTargetRic
    -
    $totalKerjaRic;

    return view('visualisasi.index', compact(
        'totalPetugasAktif',
'statusDominan',
'sisaPengerjaan',
        'totalKerjaRic',
'totalTargetRic',
'persenKerjaRic',
        'dpCount',
        'ciCount',
        'belumDP',
        'belumCI',
        'totalsudah_bayar',
        'totalBelum',
        'totalDPsudah_bayar',
        'totalCIsudah_bayar',
        'totalDPBelum',
        'totalCIBelum',
        'totalNominalsudah_bayar',
        'totalNominalBelum',
        'grandTotal',
        'totalSemuaBayar',
        'petugas',
        'kepemilikanChart',
        'persensudah_bayar',
        'persenBelum',
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
                    ) = 'SUDAH BAYAR'
                    THEN 1
                    ELSE 0
                END
            ) as sudah_bayar
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

        SUM(

            CASE

                WHEN
                    UPPER(
                        TRIM(status_bayar)
                    ) = 'SUDAH BAYAR'

                THEN
                    COALESCE(
                        nominal_bayar,
                        0
                    )

                ELSE 0

            END

        ),

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
                        ) = 'SUDAH BAYAR'

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
                        ) = 'SUDAH BAYAR'

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

            $item->persen_sudah_bayar =
                $item->total_target > 0
                ? round(
                    (
                        $item->sudah_bayar
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

    $totalsudah_bayar =
        $laporanPetugas
            ->sum('sudah_bayar');

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
            'totalsudah_bayar',
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

        $status = strtoupper(
            trim(
                $item->status_penyerahan ?? ''
            )
        );

        return empty($status)
            ? 'BELUM MELAKUKAN PENYERAHAN'
            : $status;
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

        $status = strtoupper(
            trim(
                $item->status_kepemilikan ?? ''
            )
        );

        return empty($status)
            ? 'BELUM DIKETAHUI STATUS KEPEMILIKAN'
            : $status;
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
                $item->status_bayar ?? ''
            )
        );

        return empty($status)
            ? 'BELUM MELAKUKAN PEMBAYARAN'
            : $status;
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

    $totalNominalBayar = $items

    ->filter(function ($item) {

        return strtoupper(
            trim(
                $item->status_bayar
                ?? ''
            )
        ) === 'SUDAH BAYAR';
    })

    ->sum('nominal_bayar');

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

public function importExcel(Request $request)
{
    set_time_limit(0);
    ini_set('memory_limit', '1024M');

    /*
    |--------------------------------------------------------------------------
    | VALIDASI FILE
    |--------------------------------------------------------------------------
    */

    $request->validate([
    'files' => 'required|array',
    'files.*' =>
        'mimes:xlsx,xls,csv,txt|max:51200'
]);

/*
    |--------------------------------------------------------------------------
    | PREPARE
    |--------------------------------------------------------------------------
    */

    $buffer = [];
    $uniqueData = [];
    $sheetTotals = [];

    /*
    |--------------------------------------------------------------------------
    | LOAD FILE
    |--------------------------------------------------------------------------
    */

    $files =
    $request->file('files');

    foreach ($files as $file) {
    $extension =

    
    strtolower(
        $file->getClientOriginalExtension()
    );

    /*
|--------------------------------------------------------------------------
| LOAD FILE
|--------------------------------------------------------------------------
*/

if (
    in_array(
        $extension,
        ['csv', 'txt']
    )
) {

    $spreadsheet =
        IOFactory::createReader('Csv')
        ->load(
            $file->getPathname()
        );

} else {

    $spreadsheet =
        IOFactory::load(
            $file->getPathname()
        );
}



    /*
    |--------------------------------------------------------------------------
    | SHEET YANG DIPROSES
    |--------------------------------------------------------------------------
    */

    $allowedSheets = [
        'CEK DATA I fix',
        'CEK DATA II fix',
        'CEK DATA III FIX'
    ];

    
    /*
    |--------------------------------------------------------------------------
    | LOOP SHEET
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| LOOP SHEET / CSV
|--------------------------------------------------------------------------
*/

$worksheets =
    in_array(
        $extension,
        ['csv', 'txt']
    )
    ? [$spreadsheet->getActiveSheet()]
    : $spreadsheet->getWorksheetIterator();

foreach ($worksheets as $sheet) {

        $sheetName =
            trim($sheet->getTitle());

        /*
        |--------------------------------------------------------------------------
        | HANYA SHEET TERTENTU
        |--------------------------------------------------------------------------
        */

        if (
    !in_array(
    $extension,
    ['csv', 'txt']
)
    &&
    !in_array(
        $sheetName,
        $allowedSheets
    )
) {
    continue;
}

        $beforeCount =
            count($buffer);

        $rows =
            $sheet
                ->toArray(
                    null,
                    true,
                    true,
                    false
                );

        $headerFound = false;

        foreach ($rows as $row) {

            /*
            |--------------------------------------------------------------------------
            | RAPIIKAN DATA
            |--------------------------------------------------------------------------
            */

            $row = array_map(
                function ($value) {

                    return trim(
                        (string)
                        ($value ?? '')
                    );
                },
                $row
            );

            /*
            |--------------------------------------------------------------------------
            | SKIP BARIS KOSONG
            |--------------------------------------------------------------------------
            */

            if (
                count(
                    array_filter($row)
                ) === 0
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | DETECT HEADER
            |--------------------------------------------------------------------------
            */

            if (!$headerFound) {

                $isHeader =
                    strtoupper(
                        $row[0] ?? ''
                    ) === 'NO'

                    &&

                    strtoupper(
                        $row[1] ?? ''
                    ) === 'ID PETUGAS'

                    &&

                    strtoupper(
                        $row[2] ?? ''
                    ) === 'NAMA RIC'

                    &&

                    strtoupper(
                        $row[3] ?? ''
                    ) === 'NOPOL'

                    &&

                    strtoupper(
                        $row[4] ?? ''
                    ) === 'NAMA WP';

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
                strtoupper(
                    $row[0] ?? ''
                ) === 'NO'

                ||

                strtoupper(
                    $row[3] ?? ''
                ) === 'NOPOL'
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI WAJIB
            |--------------------------------------------------------------------------
            */

            $idPetugas =
                trim(
                    $row[1] ?? ''
                );

            $nopol =
                strtoupper(
                    preg_replace(
                        '/[^A-Z0-9]/',
                        '',
                        trim(
                            $row[3] ?? ''
                        )
                    )
                );

            $namaWp =
                trim(
                    $row[4] ?? ''
                );

            if (
                empty($idPetugas)
                ||
                empty($nopol)
                ||
                empty($namaWp)
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | REMOVE DUPLICATE
            | NOPOL + ID PETUGAS
            |--------------------------------------------------------------------------
            */

            $key =
                strtoupper($nopol)
                . '_'
                . strtoupper($idPetugas);

            if (
                isset(
                    $uniqueData[$key]
                )
            ) {
                continue;
            }

            $uniqueData[$key] =
                true;

            /*
            |--------------------------------------------------------------------------
            | FORMAT MASA BERLAKU
            |--------------------------------------------------------------------------
            */

            $masaBerlaku = null;

            try {

                $rawTanggal =
                    $row[6] ?? '';

                if (
                    !empty(
                        $rawTanggal
                    )
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | EXCEL DATE NUMBER
                    |--------------------------------------------------------------------------
                    */

                    if (
                        is_numeric(
                            $rawTanggal
                        )
                    ) {

                        $masaBerlaku =
                            ExcelDate::excelToDateTimeObject(
                                $rawTanggal
                            )
                            ->format(
                                'Y-m-d'
                            );

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | FORMAT TEXT INDONESIA
                        |--------------------------------------------------------------------------
                        */

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

                        $rawTanggal =
                            str_replace(
                                array_keys(
                                    $bulan
                                ),
                                array_values(
                                    $bulan
                                ),
                                trim(
                                    $rawTanggal
                                )
                            );

                        $masaBerlaku =
                            \Carbon\Carbon::createFromFormat(
                                'd F Y',
                                $rawTanggal
                            )
                            ->format(
                                'Y-m-d'
                            );
                    }
                }

            } catch (\Throwable $e) {

                $masaBerlaku =
                    null;
            }

            /*
            |--------------------------------------------------------------------------
            | STATUS BAYAR
            |--------------------------------------------------------------------------
            */

            $statusBayar =
                strtoupper(
                    trim(
                        $row[11] ?? ''
                    )
                );

            if (
                $statusBayar
                === 'SUDAH BAYAR'
                ||
                $statusBayar
                === 'LUNAS'
            ) {

                $statusBayar =
                    'SUDAH BAYAR';

            } else {

                $statusBayar =
                    'BELUM BAYAR';
            }

            /*
            |--------------------------------------------------------------------------
            | NOMINAL BAYAR
            |--------------------------------------------------------------------------
            */

            $nominalBayar =
                null;

            if (
                !empty(
                    $row[12]
                )
            ) {

                $cleanNominal =
                    preg_replace(
                        '/[^0-9]/',
                        '',
                        $row[12]
                    );

                $nominalBayar =
                    is_numeric(
                        $cleanNominal
                    )
                    ? (float)
                        $cleanNominal
                    : null;
            }

            /*
            |--------------------------------------------------------------------------
            | METODE PEMBAYARAN
            |--------------------------------------------------------------------------
            */

            $metodePembayaran =
                strtolower(
                    trim(
                        $row[13] ?? ''
                    )
                );

            $metodePembayaran =
                str_replace(
                    ' ',
                    '',
                    $metodePembayaran
                );

            if (
                $metodePembayaran
                === 'online'
            ) {

                $metodePembayaran =
                    'online';

            } else {

                $metodePembayaran =
                    'konvensional';
            }

            /*
            |--------------------------------------------------------------------------
            | BUFFER INSERT
            |--------------------------------------------------------------------------
            */

            $buffer[] = [

                'no' =>
                    is_numeric(
                        $row[0] ?? null
                    )
                    ? (int)
                        $row[0]
                    : null,

                'id_petugas' =>
                    $idPetugas,

                'nama_ric' =>
                    $row[2] ?? null,

                'nopol' =>
                    $nopol,

                'nama_wp' =>
                    $namaWp,

                'alamat' =>
                    $row[5] ?? null,

                'masa_berlaku' =>
                    $masaBerlaku,

                'gol' =>
                    $row[7] ?? null,

                'no_hp' =>
                    $row[8] ?? null,

                'status_penyerahan' =>
                    $row[9] ?? null,

                'status_kepemilikan' =>
                    $row[10] ?? null,

                'status_bayar' =>
                    $statusBayar,

                'nominal_bayar' =>
                    $nominalBayar,

                'metode_pembayaran' =>
                    $metodePembayaran,

                'source_file' =>
                    $sheetName,

                'created_at' =>
                    now(),

                'updated_at' =>
                    now(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL PER SHEET
        |--------------------------------------------------------------------------
        */

        $afterCount =
            count($buffer);

        $sheetTotals[
            $sheetName
        ] =
            $afterCount
            -
            $beforeCount;
    }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT DATABASE
    |--------------------------------------------------------------------------
    */

    if (!empty($buffer)) {

        try {

            Swdkllj::upsert(

    $buffer,

    [
        'nopol',
        'id_petugas'
    ],

    [
        'no',
        'nama_ric',
        'nama_wp',
        'alamat',
        'masa_berlaku',
        'gol',
        'no_hp',
        'status_penyerahan',
        'status_kepemilikan',
        'status_bayar',
        'nominal_bayar',
        'metode_pembayaran', // <- TAMBAH INI
        'source_file',
        'updated_at'
    ]
);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' =>
                    $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

    AppSetting::updateOrCreate(
    [
        'key' =>
            'last_update'
    ],
    [
        'value' =>
            json_encode([
                'time' => now(),
                'source' =>
                    'Import File'
            ])
    ]
);

    return response()->json([
        'success' => true,
        'message' =>
            'Import File berhasil',
        'total' =>
            count($buffer),
        'per_sheet' =>
            $sheetTotals,
    ]);
}



}