<?php

namespace App\Http\Controllers\API;

use App\Models\Produk;
use App\Models\Wisata;
use App\Models\Kesenian;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request) 
    {
        // ambil 5 data terbaru masing-masing
        $kesenian = Kesenian::with('files')->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'kesenian',
                'data' => $item,
            ];
        });
        $produk   = Produk::with('files')->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'produk',
                'data' => $item,
            ];
        });
        $wisata = Wisata::with('files')->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'wisata',
                'data' => $item,
            ];
        });

        $randomProduk = Produk::with('files','toko')->inRandomOrder()->take(8)->get();

        // gabungkan koleksi
        $merged = $kesenian->concat($produk)->concat($wisata);

        // urutkan lagi berdasarkan created_at
        $sorted = $merged->sortByDesc   ('created_at')->values();

        $data = [
            "explore" => $sorted,
            "produk" => $randomProduk
        ];

        return ApiResponse::success($data);
    }
}
