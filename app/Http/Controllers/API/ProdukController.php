<?php

namespace App\Http\Controllers\API;

use App\Models\Produk;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use App\Http\Resources\ProdukDetailResource;

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
        $result = ProdukResource::collection($data->items());
        
        return ApiResponse::paginated($data, "Daftar produk berhasil diambil", $result);
    }

    public function show($id)
    {
        $produk = Produk::with('files')->find($id);

        if (!$produk) {
            return ApiResponse::error("Data produk tidak ditemukan", 404);
        }

        // upsert stats
        $produk->stat()->updateOrCreate(
            ['statable_id' => $produk->id, 'statable_type' => Produk::class],
            ['view_count' => \DB::raw('COALESCE(view_count, 0) + 1')]
        );

        $random = random_produk(4, $id);
        $produk->lainnya = ProdukResource::collection($random);

        return ApiResponse::success(new ProdukDetailResource($produk), "Detail produk berhasil diambil", 200);
    }
}
