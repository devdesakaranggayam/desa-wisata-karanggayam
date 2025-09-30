<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Hadiah;
use App\Models\UserHadiah;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\HadiahResource;

class HadiahController extends Controller
{
    public function userHadiah(Request $request)
    {
        $user = auth('api')->user();
        $hadiah = get_claimable_reward($user->id);

        return ApiResponse::success(HadiahResource::collection($hadiah));
    }

    public function klaimHadiah(Request $request)
    {
        $user = auth('api')->user();

        // cek apakah sudah klaim hadiah yang sama di hari ini
        $sudahKlaim = UserHadiah::where('user_id', $user->id)
            ->where('hadiah_id', $request->hadiah_id)
            ->whereDate('claimed_at', Carbon::today())
            ->exists();

        if ($sudahKlaim) {
            return ApiResponse::error('', 400, 'Kamu sudah klaim hadiah ini hari ini.');
        }

        UserHadiah::create([
            "user_id"   => $user->id,
            "hadiah_id" => $request->hadiah_id,
            "claimed_at" => Carbon::now()
        ]);

        return ApiResponse::success(null, 'Hadiah berhasil diklaim');
    }
}
