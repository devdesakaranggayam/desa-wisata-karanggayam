<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index()
    {
        $toko = Toko::all();
        return view('dashboard.toko.index', compact('toko'));
    }

    public function create()
    {
        return view('dashboard.toko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        Toko::create($request->only(['nama', 'no_hp']));

        return redirect()->route('toko.index')->with('success', 'Toko berhasil ditambahkan.');
    }

    public function edit(Toko $toko)
    {
        return view('dashboard.toko.edit', compact('toko'));
    }

    public function update(Request $request, Toko $toko)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $toko->update($request->only(['nama', 'no_hp', 'alamat']));

        return redirect()->route('toko.index')->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Toko $toko)
    {
        if ($toko->products()->exists()) {
            return redirect()->route('toko.index')
                ->with('error', 'Tidak dapat menghapus toko karena masih memiliki produk.');
        }
        $toko->delete();
        return redirect()->route('toko.index')->with('success', 'Toko berhasil dihapus.');
    }
}
