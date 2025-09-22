<?php

namespace App\Http\Controllers\API;

use App\Models\Wisata;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExploreResource;

class WisataController extends Controller
{
    public function show($id)
    {
        $wisata = Wisata::with('files')->find($id);

        if (!$wisata) {
            return ApiResponse::error("Data wisata tidak ditemukan", 404);
        }

        // upsert stats
        $wisata->stat()->updateOrCreate(
            ['statable_id' => $wisata->id, 'statable_type' => Wisata::class],
            ['view_count' => \DB::raw('COALESCE(view_count, 0) + 1')]
        );

        $wisata->lainnya = ExploreResource::collection(random_wisata(8, $id));
        $wisata->tipe = "wisata";

        return ApiResponse::success(new ExploreDetailResource($wisata), "Detail wisata berhasil diambil", 200);
    }
}
