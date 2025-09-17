<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with(['toko', 'files'])->get();
        return view('dashboard.produk.index', compact('produk'));
    }

    public function create()
    {
        $tokos = Toko::all();
        return view('dashboard.produk.create', compact('tokos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'toko_id' => 'required|exists:toko,id',
            'files.*' => 'nullable|file|max:2048',
        ]);

        $produk = Produk::create($request->only(['nama', 'harga', 'deskripsi', 'toko_id']));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('produk', 'public');
                $produk->files()->create([
                    'nama' => $file->getClientOriginalName(),
                    'tipe_file' => $file->getClientMimeType(),
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function show($id)
    {
        $produk = Produk::with(['toko', 'files'])->findOrFail($id);
        return view('dashboard.produk.detail', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        $tokos = Toko::all();
        return view('dashboard.produk.edit', compact('produk', 'tokos'));
    }

    public function update(Request $request, $id)
    {

        $produk = Produk::findOrFail($id);
        $produk->update($request->only(['nama', 'harga', 'deskripsi', 'toko_id']));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('produk', 'public');
                $produk->files()->create([
                    'nama' => $file->getClientOriginalName(),
                    'tipe_file' => $file->getClientMimeType(),
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $produk = Produk::with('files')->findOrFail($id);

        // hapus file fisik dari storage
        foreach ($produk->files as $file) {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->delete();
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }

    
    public function removeFile(Produk $produk, File $file)
    {
        // cek kalau file memang milik kesenian ini
        if ($file->fileable_id !== $produk->id) {
            return response()->json(["success" => false], 404);
        }

        // hapus dari storage publik
        if (\Storage::disk('public')->exists($file->path)) {
            \Storage::disk('public')->delete($file->path);
        }

        // hapus record
        $file->delete();

        return response()->json(["success" => true], 200);
    }
}
