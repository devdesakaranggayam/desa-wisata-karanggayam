<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'nomor_hp'  => 'required|string|max:20|unique:users',
        ]);

        $user = User::create($request->all());

        return ApiResponse::success($user, "Registrasi berhasil", 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('nomor_hp', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return ApiResponse::error("Nomor hp atau password salah", 401);
        }

        return ApiResponse::success([
            'user'  => Auth::guard('api')->user(),
            'token' => $token,
        ], "Login berhasil");
    }

    public function me()
    {
        return ApiResponse::success(Auth::guard('api')->user(), "Profile user");
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return ApiResponse::success([], "Logout berhasil");
    }

    public function refresh()
    {
        return ApiResponse::success([
            'token' => Auth::guard('api')->refresh(),
        ], "Token berhasil diperbarui");
    }
}
