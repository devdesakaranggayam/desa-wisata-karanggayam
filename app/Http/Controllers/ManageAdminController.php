<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class ManageAdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('dashboard.admin.index', compact('admins'));
    }

    public function create()
    {
        return view('dashboard.admin.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'nama'      => 'required|string|max:100',
            'username'  => 'required|string|unique:admins',
            'email'     => 'required|email|unique:admins',
            'password'  => 'required|min:8',
            'nomor_hp'  => 'required|string|max:20|unique:admins',
        ]);

        Admin::create($request->only([
            'nama',
            'email',
            'username',
            'password',
            'nomor_hp'
        ]));

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        return view('dashboard.admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:admins,email,' . $admin->id,
            'username'  => 'required|string|unique:admins,username,' . $admin->id,
        ]);

        $admin->update($request->only(['nama', 'nomor_hp', 'alamat', 'username', 'email']));
        
        if ($request->password) {
            $admin->update($request->only(['password']));
        }

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
