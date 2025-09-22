<?php

namespace App\Http\Controllers\API;

use App\Models\Produk;
use App\Models\Wisata;
use App\Models\Carousel;
use App\Models\Kesenian;
use App\Helpers\ApiResponse;
use App\Models\ProdukBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use App\Http\Resources\ExploreResource;
use App\Http\Resources\ProdukBannerResource;

class HomeController extends Controller
{
    public function index(Request $request) 
    {
        // ambil 5 data terbaru masing-masing
        $kesenian = random_kesenian(4, null);
        $wisata = random_wisata(4, null);

        $randomProduk = Produk::with('files','toko')->inRandomOrder()->take(8)->get();

        // gabungkan koleksi
        $merged = $kesenian->concat($wisata);

        // urutkan lagi berdasarkan created_at
        $sorted = $merged->sortByDesc('created_at')->values();

        $banner = Carousel::with('files')->where('identifier', 'home_banner')->first();
        $bannerProduk = Carousel::with(['files' => function ($q) {
            $q->orderBy('urutan', 'asc');
        }])
        ->where('identifier', 'home_produk')
        ->first();

        $data = [
            "home_banner" => $banner->files()->first()->file_url ?? null,
            "produk_banner" => ProdukBannerResource::collection($bannerProduk->files),
            "explore" => ExploreResource::collection($sorted),
            "produk" => ProdukResource::collection($randomProduk),
            
        ];

        return ApiResponse::success($data);
    }
}
