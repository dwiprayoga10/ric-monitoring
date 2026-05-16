<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/data-swdkllj', [DashboardController::class, 'data'])
        ->name('data.swdkllj');

    Route::get('/visualisasi', [DashboardController::class, 'visualisasi'])
        ->name('visualisasi');

    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */

    Route::get('/laporan', [DashboardController::class, 'laporan'])
        ->name('laporan');

    // TAMBAHAN BARU
    Route::get(
        '/laporan-petugas/{nama_ric}',
        [DashboardController::class, 'laporanPetugas']
    )->name('laporan.petugas');

    /*
    |--------------------------------------------------------------------------
    | IMPORT
    |--------------------------------------------------------------------------
    */

    Route::post('/import-excel', [DashboardController::class, 'import'])
        ->name('import.excel');
});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';