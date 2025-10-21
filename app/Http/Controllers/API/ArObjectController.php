<?php

namespace App\Http\Controllers\API;

use App\Models\ArObject;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArObjectResource;

class ArObjectController extends Controller
{
    public function index()
    {
        $data = ArObject::latest()->paginate(10);
        $result = ArObjectResource::collection($data->items());
        return ApiResponse::paginated($data, 'OK', $result);
    }
}
