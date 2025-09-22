<?php

namespace App\Http\Controllers\API;

use App\Models\Produk;
use App\Models\Wisata;
use App\Models\Kesenian;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExploreResource;
use App\Http\Resources\ExploreDetailResource;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('keyword');

        if (!$query) {
            return ApiResponse::error("Parameter 'keyword' wajib diisi.", 422);
        }

        // Cari di Kesenian
        $kesenian = Kesenian::where('nama', 'like', '%' . $query . '%')
            ->orWhere('deskripsi', 'like', '%' . $query . '%')
            ->with(['files'])
            ->get()
            ->map(function ($item) {
                $item->type = 'kesenian';
                return $item;
            });

        // Cari di wisata
        $wisata = Wisata::where('nama', 'like', '%' . $query . '%')
            ->orWhere('deskripsi', 'like', '%' . $query . '%')
            ->with(['files'])
            ->get()
            ->map(function ($item) {
                $item->type = 'wisata';
                return $item;

            });

        // Gabung hasil
        $result = $kesenian->concat($wisata)->values();


        return ApiResponse::success(ExploreResource::collection($result), "Hasil pencarian untuk '{$query}'", 200);
    }

    public function detail(Request $request)
    {
        $type = $request->tipe;
        $id = $request->id;

        $data = null;
        if ($type == 'kesenian') {
            $data = Kesenian::with('files')->find($id);
            $data["lainnya"] = ExploreResource::collection(random_kesenian(8, $data->id));
        } elseif ($type == 'wisata') {
            $data = Wisata::with('files')->find($id);
            $data["lainnya"] = ExploreResource::collection(random_wisata(8, $data->id));
        }
        $data["tipe"] = $type;

        return ApiResponse::success(new ExploreDetailResource($data), "", 200);
    }
}
