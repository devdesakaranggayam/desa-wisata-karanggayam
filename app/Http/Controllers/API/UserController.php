<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth('api')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return ApiResponse::error('Password saat ini salah.', 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return ApiResponse::success([], 'Password berhasil diperbarui.', 200);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'nomor_hp' => 'sometimes|string|max:20',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validasi gagal.', 422, $validator->errors());
        }

        $user->update($validator->validated());

        return ApiResponse::success($user, 'Profil berhasil diperbarui.', 200);
    }

    public function updateProfilePicture(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validasi gagal.', 422, $validator->errors());
        }

        // Upload file
        $file = $request->file('profile_pic');
        $path = $file->store('profile_pic', 'public');

        // Hapus foto lama jika ada
        if ($user->profile_pic) {
            Storage::disk('public')->delete($user->profile_pic);
        }

        // Simpan path baru
        $user->update([
            'profile_pic' => $path
        ]);

        return ApiResponse::success($user, 'Foto profil berhasil diperbarui.', 200);
    }
}
