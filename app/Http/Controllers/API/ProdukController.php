<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('files');

        // search by nama
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // filter by toko_id
        if ($request->has('toko_id')) {
            $query->where('toko_id', $request->toko_id);
        }

        // filter by harga range
        if ($request->has('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }
        if ($request->has('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        // sorting
        if ($request->has('sort_by')) {
            $sortBy = $request->get('sort_by', 'id');
            $sortDir = $request->get('sort_dir', 'asc');
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        $perPage = $request->get('per_page', 10);
        $data = $query->paginate($perPage);

        return ApiResponse::paginated($data, "Daftar produk berhasil diambil", 200);
    }

    public function show($id)
    {
        $produk = Produk::with('files')->find($id);

        if (!$produk) {
            return ApiResponse::error("Data produk tidak ditemukan", 404);
        }

        return ApiResponse::success($produk, "Detail produk berhasil diambil", 200);
    }
}
