<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|unique:users,nomor_hp',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'pesan' => 'Registrasi berhasil',
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'nomor_hp' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('nomor_hp', $request->nomor_hp)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['pesan' => 'Nomor HP atau password salah'], 401);
        }

        return response()->json([
            'pesan' => 'Login berhasil',
            'data' => $user
        ]);
    }
}
