<?php

namespace App\Http\Controllers\API;

use App\Models\Wisata;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $random = Wisata::with('files')->inRandomOrder()->take(8)->get();

        $wisata->lainnya = $random;


        return ApiResponse::success($wisata, "Detail wisata berhasil diambil", 200);
    }
}
