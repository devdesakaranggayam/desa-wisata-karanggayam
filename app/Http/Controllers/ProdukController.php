<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with(['toko', 'files'])->latest()->get();
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
        ]);

        $produk = Produk::create($request->only(['nama', 'harga', 'deskripsi', 'toko_id']));

        if ($request->has('files')) {
            foreach ($request->input('files') as $index => $fileInput) {
                $uploadedFile = $request->file("files.$index.file");
                if ($uploadedFile) {
                    $path = $uploadedFile->store('produk', 'public');
                    $ext = $uploadedFile->getClientOriginalExtension();
                    $produk->files()->create([
                        'nama'      => Str::random(20) . '.' . $ext,
                        'path'      => $path,
                        'tipe_file' => $uploadedFile->getClientMimeType(),
                        'urutan'    => $fileInput['urutan'] ?? $index,
                    ]);
                }
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

        if ($request->has('files')) {
            foreach ($request->input('files') as $index => $fileInput) {
                $uploadedFile = $request->file("files.$index.file");
                if ($uploadedFile) {
                    $path = $uploadedFile->store('produk', 'public');
                    $ext = $uploadedFile->getClientOriginalExtension();
                    $produk->files()->create([
                        'nama'      => Str::random(20) . '.' . $ext,
                        'path'      => $path,
                        'tipe_file' => $uploadedFile->getClientMimeType(),
                        'urutan'    => $fileInput['urutan'] ?? $index,
                    ]);
                }
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
