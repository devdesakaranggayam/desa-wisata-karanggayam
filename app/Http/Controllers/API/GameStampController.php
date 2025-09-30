<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\GameStamp;
use App\Models\UserStamp;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Http\Resources\UserStampList;
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

    private function getUserStampData($userId)
    {
        $data = UserStamp::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->with('gameStamp')
            ->get();
        $gameStampCount = GameStamp::count();
        $userStampCount = $data->sum('jumlah_stamp');
        return [
            "stamp_count" => [
                "total_stamp" => $gameStampCount,
                "user_stamp" => $userStampCount
            ],
            "stamp_list" => UserStampList::collection($data)
        ];
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
            'game_stamp_id' => 'required|exists:game_stamps,id'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('', 400, $validator->errors()->first());
        }

        // cek apakah sudah ada record untuk user + game_stamp di hari ini
        $exists = UserStamp::where('user_id', $user->id)
            ->where('game_stamp_id', $request->game_stamp_id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($exists) {
            return ApiResponse::error('', 400, 'Kamu sudah klaim stamp untuk game ini hari ini.');
        }

        // buat record baru
        UserStamp::create([
            'user_id'       => $user->id,
            'game_stamp_id' => $request->game_stamp_id,
            'jumlah_stamp'  => 1
        ]);

        $data = $this->getUserStampData($user->id);

        return ApiResponse::success($data, 'Stamp berhasil ditambahkan');
    }

    public function getUserStamps(Request $request)
    {
        $user = auth('api')->user();
        $data = $this->getUserStampData($user->id);
        return ApiResponse::success($data, 'Stamp berhasil ditambahkan');
    }
}
