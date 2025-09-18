<?php

namespace App\Http\Controllers\API;

use App\Models\Produk;
use App\Models\Wisata;
use App\Models\Kesenian;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('keyword');

        if (!$query) {
            return ApiResponse::error("Parameter 'keyword' wajib diisi.", 422);
        }

        // Cari di Produk
        $produk = Produk::where('nama', 'like', '%' . $query . '%')
            ->orWhere('deskripsi', 'like', '%' . $query . '%')
            ->with(['files','toko'])
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'produk',
                    'data' => $item,
                ];
            });

        // Cari di Kesenian
        $kesenian = Kesenian::where('nama', 'like', '%' . $query . '%')
            ->orWhere('deskripsi', 'like', '%' . $query . '%')
            ->with(['files'])
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'kesenian',
                    'data' => $item,
                ];
            });

        // Cari di wisata
        $wisata = Wisata::where('nama', 'like', '%' . $query . '%')
            ->orWhere('deskripsi', 'like', '%' . $query . '%')
            ->with(['files'])
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'wisata',
                    'data' => $item,
                ];
            });

        // Gabung hasil
        $result = $produk->concat($kesenian)->concat($wisata)->values();

        return ApiResponse::success($result, "Hasil pencarian untuk '{$query}'", 200);
    }
}
