<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KesenianController;

Route::get('/', function () {
    return view('dashboard.kesenian.index');
});


Route::resource('kesenian', KesenianController::class);
Route::delete('/kesenian/{kesenian}/file/{file}', [KesenianController::class, 'removeFile'])->name('kesenian.removeFile');

Route::resource('toko', TokoController::class);

Route::resource('produk', ProdukController::class);
Route::delete('/produk/{produk}/file/{file}', [ProdukController::class, 'removeFile'])->name('produk.removeFile');

