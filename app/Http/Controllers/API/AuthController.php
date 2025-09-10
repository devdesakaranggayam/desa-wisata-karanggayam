<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    
    public function requestReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(10000, 99999); // 5 digit

        $hashedOtp = Hash::make($otp);

        PasswordReset::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $hashedOtp, 'created_at' => Carbon::now()]
        );

        // Kirim email
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Kode Reset Password Anda');
        });

        return response()->json(['message' => 'Kode OTP telah dikirim ke email Anda.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $reset = PasswordReset::where('email', $request->email)->first();

        if (!$reset || !Hash::check($request->otp, $reset->otp)) {
            return response()->json(['message' => 'OTP salah atau tidak ditemukan'], 400);
        }

        // Token sementara
        $token = Str::random(60);
        cache()->put("password_reset_token_{$request->email}", $token, now()->addMinutes(15));

        return response()->json(['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $cachedToken = cache()->get("password_reset_token_{$request->email}");

        if (!$cachedToken || $cachedToken !== $request->token) {
            return response()->json(['message' => 'Token tidak valid atau sudah kedaluwarsa'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token dan otp
        cache()->forget("password_reset_token_{$request->email}");
        PasswordReset::where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil direset']);
    }

}
