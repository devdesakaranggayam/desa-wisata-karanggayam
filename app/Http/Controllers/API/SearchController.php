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
            $explore = Wisata::with('files')->inRandomOrder()->take(8)->get();
        } else {
            $explore = search_wisata($query, null, $request->all());
            $produk = search_produk($query, null, $request->all());
        }

        $result["explore"] = ExploreResource::collection($explore);
        $result["produk"] = ProdukResource::collection($produk);

        return ApiResponse::success($result, "Hasil pencarian untuk '{$query}'", 200);
    }

    public function detail(Request $request)
    {
        $data = Wisata::with('files')->findOrFail($request->id);
        return ApiResponse::success(new ExploreDetailResource($data), "", 200);
    }

    public function explore(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tipe    = $request->input('tipe', 'all');
        $keyword = $request->input('keyword');

        $query = Wisata::query()
            ->when($tipe !== 'all', function ($q) use ($tipe) {
                $q->where('type', $tipe);
            })
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%");
            });

        $data = $query->paginate($perPage);

        $result = ExploreResource::collection($data);

        return ApiResponse::paginated($result, "");
    }


}
