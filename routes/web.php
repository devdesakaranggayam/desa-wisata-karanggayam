<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HadiahController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\KesenianController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameStampController;
use App\Http\Controllers\ManageAdminController;

Route::get('', function () {
    return redirect()->route('dashboard');
});


Route::middleware('auth:admin')->group(function () {
    Route::delete('wisata/{wisata}/file/{file}', [WisataController::class, 'removeFile'])->name('wisata.removeFile');
    Route::delete('kesenian/{kesenian}/file/{file}', [KesenianController::class, 'removeFile'])->name('kesenian.removeFile');
    Route::delete('produk/{produk}/file/{file}', [ProdukController::class, 'removeFile'])->name('produk.removeFile');
    Route::delete('/carousel/{carousel}/file/{file}', [CarouselController::class, 'destroyFile'])->name('carousel.file.destroy');
    Route::delete('game-stamps/{game}/file/{file}', [GameStampController::class, 'destroyFile'])->name('game-stamps.file.destroy');
});

Route::prefix('dashboard')->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::get('index', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('wisata', WisataController::class);
        Route::resource('kesenian', KesenianController::class);
        Route::resource('toko', TokoController::class);

        Route::resource('game-stamps', GameStampController::class);

        Route::prefix('akun')->group(function () {
            Route::resource('pengguna', UserController::class);
            Route::resource('admin', ManageAdminController::class);
        });

        Route::resource('hadiah', HadiahController::class);
        Route::resource('produk', ProdukController::class);
        Route::resource('carousel', CarouselController::class)->except([
            'create', 'destroy'
        ]);
        
        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login');
});


