<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH (REGISTER)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // halaman register
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    // proses simpan user
    Route::post('/register', [RegisteredUserController::class, 'store']);
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

    Route::get(
        '/laporan-petugas/{nama_ric}',
        [DashboardController::class, 'laporanPetugas']
    )->name('laporan.petugas');


    
    /*
    
    |--------------------------------------------------------------------------
    | IMPORT
    |--------------------------------------------------------------------------
    */

    Route::post(
    '/import-excel',
    [DashboardController::class, 'importExcel']
)->name('import.excel');
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