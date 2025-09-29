<?php

namespace App\Http\Controllers\API;

use App\Models\GameStamp;
use App\Models\UserStamp;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameStampResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GameDetailResource;

class GameStampController extends Controller
{
    public function index(Request $request)
    {
        $data = GameStamp::all();
        return ApiResponse::success(GameResource::collection($data));
    }

    public function show(Request $request, $id)
    {
        $data = GameStamp::with('questions.answers')->where('id', $id)->first();
        return ApiResponse::success(new GameDetailResource($data));
    }

    public function createUserStamp(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'game_stamp_id' => 'required|exists:game_stamps,id',
            'jumlah_stamp'  => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('', 400, $validator->errors()->first());
        }

        $userStamp = UserStamp::firstOrNew([
            'user_id'       => $user->id,
            'game_stamp_id' => $request->game_stamp_id,
        ]);

        // tambahkan jumlah
        $userStamp->jumlah_stamp += $request->jumlah_stamp;
        $userStamp->save();

        return ApiResponse::success($userStamp, 'Stamp berhasil ditambahkan');
    }


}
