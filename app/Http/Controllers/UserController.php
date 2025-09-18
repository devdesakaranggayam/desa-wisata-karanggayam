<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.pengguna.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'nomor_hp'  => 'required|string|max:20|unique:users',
        ]);

        User::create($request->only([
            'nama',
            'email',
            'password',
            'nomor_hp'
        ]));

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        return view('dashboard.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $pengguna->id,
            'nomor_hp'  => 'required|string|max:20|unique:users,nomor_hp,' . $pengguna->id,
            'password'  => 'nullable|min:6',
        ]);

        $pengguna->update($request->only(['nama', 'nomor_hp', 'email']));
        
        if ($request->password) {
            $pengguna->update($request->only(['password']));
        }

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
