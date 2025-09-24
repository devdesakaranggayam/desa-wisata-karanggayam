<?php

namespace App\Http\Controllers\API;

use App\Models\Produk;
use App\Models\Wisata;
use App\Models\Kesenian;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use App\Http\Resources\ExploreResource;
use App\Http\Resources\ExploreDetailResource;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('keyword');

        $result = [];
        if (!$query) {
            $produk = random_produk(4);
            $kesenian = random_kesenian(2);
            $wisata = random_wisata(2);
        } else {
            $produk = search_produk($query, null, $request->all());
            $wisata = search_wisata($query, $request->all());
            $kesenian = search_kesenian($query, $request->all());
        }

        $explore = $kesenian->concat($wisata)->values();
        $result["explore"] = ExploreResource::collection($explore);
        $result["produk"] = ProdukResource::collection($produk);

        return ApiResponse::success($result, "Hasil pencarian untuk '{$query}'", 200);
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

    public function explore(Request $request)
    {
        $tipe = $request->tipe;
        if (!$tipe || $tipe == "all") {
            $kesenian = random_kesenian(4);
            $wisata = random_wisata(4);
            $explore = $kesenian->concat($wisata)->values();

        } else {
            if ($tipe == "kesenian") {
                $explore = random_kesenian(8);
            } else {
                $explore = random_wisata(8);
            }
        }
        $result = ExploreResource::collection($explore);
        return ApiResponse::success($result, "", 200);
    }
}
