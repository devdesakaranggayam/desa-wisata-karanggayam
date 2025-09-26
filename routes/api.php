<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\TokoController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\WisataController;
use App\Http\Controllers\API\KesenianController;
use App\Http\Controllers\API\GameStampController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('kesenian', [KesenianController::class, 'index']);
Route::get('kesenian/{id}', [KesenianController::class, 'show'])->name('api.kesenian.show');

Route::get('produk', [ProdukController::class, 'index']);
Route::get('produk/{id}', [ProdukController::class, 'show'])->name('api.produk.show');

Route::get('toko', [TokoController::class, 'index']);
Route::get('toko/{id}', [TokoController::class, 'show']);

Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('forgot-password', [AuthController::class, 'requestReset']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::get('search', [SearchController::class, 'globalSearch']);
Route::get('explore', [SearchController::class, 'explore']);
Route::get('explore/detail', [SearchController::class, 'detail'])->name('api.search.detail');
Route::get('home', [HomeController::class, 'index']);

Route::get('wisata/{id}', [WisataController::class, 'show'])->name('api.wisata.show');

// Protected routes
Route::middleware('auth.api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('profile', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::prefix('akun')->group(function () {
        Route::post('update-password', [UserController::class, 'updatePassword']);
        Route::post('update-profile', [UserController::class, 'updateProfile']);
        Route::post('update-profile-picture', [UserController::class, 'updateProfilePicture']);
    });

    Route::get('game-stamps', [GameStampController::class, 'index']);
    Route::post('user-stamps', [GameStampController::class, 'createUserStamp']);
});

