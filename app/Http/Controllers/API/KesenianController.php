<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kesenian;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class KesenianController extends Controller
{
    public function index(Request $request)
    {
        $query = Kesenian::with('files');

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

        return ApiResponse::paginated($data, "Daftar kesenian berhasil diambil", 200);
    }

    public function show($id)
    {
        $kesenian = Kesenian::with('files')->find($id);

        if (!$kesenian) {
            return ApiResponse::error("Data kesenian tidak ditemukan", 404);
        }

        // upsert stats
        $kesenian->stat()->updateOrCreate(
            ['statable_id' => $kesenian->id, 'statable_type' => Kesenian::class],
            ['view_count' => \DB::raw('COALESCE(view_count, 0) + 1')]
        );

        $random = Kesenian::with('files')->inRandomOrder()->take(8)->get();
        $kesenian->lainnya = $random;

        return ApiResponse::success($data, "Detail kesenian berhasil diambil", 200);
    }
}
