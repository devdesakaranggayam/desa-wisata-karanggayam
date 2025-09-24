<?php

namespace App\Http\Controllers\API;

use App\Models\Toko;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TokoResource;

class TokoController extends Controller
{
    public function index(Request $request)
    {
        $query = Toko::query();

        // search by nama
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // filter by no_hp
        if ($request->has('no_hp')) {
            $query->where('no_hp', $request->no_hp);
        }

        // sorting
        if ($request->has('sort_by')) {
            $sortBy = $request->get('sort_by', 'id');
            $sortDir = $request->get('sort_dir', 'asc');
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        $data = $query->get();
        return ApiResponse::success(TokoResource::collection($data), "Daftar toko berhasil diambil", 200);
    }

    public function show($id)
    {
        $toko = Toko::find($id);

        if (!$toko) {
            return ApiResponse::error("Data toko tidak ditemukan", 404);
        }

        return ApiResponse::success(new TokoResource($toko), "Detail toko berhasil diambil", 200);
    }
}
