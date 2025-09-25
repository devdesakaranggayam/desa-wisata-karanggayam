<?php

namespace App\Http\Controllers\API;

use App\Models\Wisata;
use App\Models\Kesenian;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExploreResource;
use App\Http\Resources\ExploreDetailResource;

class KesenianController extends Controller
{
    public function index(Request $request)
    {
        $query = Wisata::kesenian()->with('files');

        // search by nama
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // filter by updated_at >=
        if ($request->has('updated_after')) {
            $query->where('updated_at', '>=', $request->updated_after);
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

        $result = ExploreResource::collection($data);

        return ApiResponse::paginated($result, "Daftar kesenian berhasil diambil");
    }


    public function show($id)
    {
        $kesenian = Wisata::with('files')->find($id);

        if (!$kesenian) {
            return ApiResponse::error("Data kesenian tidak ditemukan", 404);
        }

        // upsert stats
        $kesenian->stat()->updateOrCreate(
            ['statable_id' => $kesenian->id, 'statable_type' => Wisata::class],
            ['view_count' => \DB::raw('COALESCE(view_count, 0) + 1')]
        );

        $kesenian->lainnya = ExploreResource::collection(random_kesenian(8, $id));

        return ApiResponse::success(new ExploreDetailResource($kesenian), "Detail kesenian berhasil diambil", 200);
    }
}
