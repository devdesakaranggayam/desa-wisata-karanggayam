<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KesenianController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageAdminController;

Route::get('dashboard', function () {
    return redirect()->route('kesenian.index');
});

Route::prefix('dashboard')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login');

    Route::middleware('auth:admin')->group(function () {
        Route::resource('kesenian', KesenianController::class);
        Route::delete('kesenian/{kesenian}/file/{file}', [KesenianController::class, 'removeFile'])->name('kesenian.removeFile');
        
        Route::resource('toko', TokoController::class);

        Route::prefix('akun')->group(function () {
            Route::resource('pengguna', UserController::class);
            Route::resource('admin', ManageAdminController::class);
        });

        Route::resource('produk', ProdukController::class);
        Route::delete('produk/{produk}/file/{file}', [ProdukController::class, 'removeFile'])->name('produk.removeFile');

        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});


