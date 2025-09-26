<?php

namespace App\Http\Controllers\API;

use App\Models\GameStamp;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameStampResource;

class GameStampController extends Controller
{
    public function index(Request $request)
    {
        $data = GameStamp::get([
            'id',
            'nama',
            'type',
            'passing_score',
            'nama',
            'x',
            'y',
            'icon_path',
        ]);

        return ApiResponse::success($data,'');
    }
}
