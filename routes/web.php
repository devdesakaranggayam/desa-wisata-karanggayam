<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KesenianController;
use App\Http\Controllers\AdminAuthController;

Route::get('admin', function () {
    return redirect()->route('kesenian.index');
});

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login');

    Route::middleware('auth:admin')->group(function () {
        Route::resource('kesenian', KesenianController::class);
        Route::delete('kesenian/{kesenian}/file/{file}', [KesenianController::class, 'removeFile'])->name('kesenian.removeFile');
        
        Route::resource('toko', TokoController::class);
        Route::resource('pengguna', UserController::class);
        
        Route::resource('produk', ProdukController::class);
        Route::delete('produk/{produk}/file/{file}', [ProdukController::class, 'removeFile'])->name('produk.removeFile');

        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});


