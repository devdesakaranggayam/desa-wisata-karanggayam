<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\GameStamp;
use App\Models\UserStamp;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\UserStampList;
use App\Http\Resources\GameStampResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GameDetailResource;

class GameStampController extends Controller
{
    public function index(Request $request)
    {
        $data = GameStamp::with('files')->get();
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

        $data = get_user_stamp($user->id);

        return ApiResponse::success($data, 'Stamp berhasil ditambahkan');
    }

    public function getUserStamps(Request $request)
    {
        $user = auth('api')->user();
        $data = get_user_stamp($user->id);
        return ApiResponse::success($data, 'Stamp berhasil ditambahkan');
    }

    public function checkGapuraImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $response = Http::attach(
            'file',
            file_get_contents($request->file('image')),
            $request->file('image')->getClientOriginalName()
        )->post('http://127.0.0.1:8001/check-similarity');

        return $response->json();
    }
}
