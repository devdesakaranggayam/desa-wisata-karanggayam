<?php

namespace App\Http\Controllers;

use App\Models\Hadiah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HadiahController extends Controller
{
    public function index()
    {
        $hadiah = Hadiah::with('thumbnail')->latest()->get();
        return view('dashboard.hadiah.index', compact('hadiah'));
    }

    public function create()
    {
        return view('dashboard.hadiah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'min_stamp'  => 'required|integer|min:0',
            'thumbnail'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $hadiah = Hadiah::create($validated);

        // Simpan thumbnail kalau ada
        if ($request->hasFile('thumbnail')) {
            $uploadedFile = $request->file('thumbnail');
            $ext = $uploadedFile->getClientOriginalExtension();

            // simpan ke storage/app/public/thumbnails
            $path = $uploadedFile->store('thumbnails', 'public');

            // buat nama unik file
            $namaFile = Str::random(20) . '.' . $ext;

            $hadiah->thumbnail()->create([
                'nama'      => $namaFile,
                'path'      => $path,
                'tipe_file' => $uploadedFile->getClientMimeType(),
                'urutan'    => 0, // default 0 karena cuma 1 thumbnail
            ]);
        }

        return redirect()
            ->route('hadiah.index')
            ->with('success', 'Hadiah berhasil ditambahkan.');
    }


    public function show(Hadiah $hadiah)
    {
        return view('dashboard.hadiah.show', compact('hadiah'));
    }

    public function edit(Hadiah $hadiah)
    {
        return view('dashboard.hadiah.edit', compact('hadiah'));
    }

    public function update(Request $request, Hadiah $hadiah)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'min_stamp'  => 'required|integer|min:0',
            'thumbnail'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // update data hadiah
        $hadiah->update($validated);

        // jika ada file thumbnail baru
        if ($request->hasFile('thumbnail')) {
            $uploadedFile = $request->file('thumbnail');
            $ext = $uploadedFile->getClientOriginalExtension();

            // simpan ke storage/app/public/thumbnails
            $path = $uploadedFile->store('thumbnails', 'public');

            // nama unik
            $namaFile = Str::random(20) . '.' . $ext;

            if ($hadiah->thumbnail) {
                // update record morphOne
                $hadiah->thumbnail()->update([
                    'nama'      => $namaFile,
                    'path'      => $path,
                    'tipe_file' => $uploadedFile->getClientMimeType(),
                    'urutan'    => 0,
                ]);
            } else {
                // buat record baru kalau belum ada
                $hadiah->thumbnail()->create([
                    'nama'      => $namaFile,
                    'path'      => $path,
                    'tipe_file' => $uploadedFile->getClientMimeType(),
                    'urutan'    => 0,
                ]);
            }
        }

        return redirect()
            ->route('hadiah.index')
            ->with('success', 'Hadiah berhasil diperbarui.');
    }


    public function destroy(Hadiah $hadiah)
    {
        $hadiah->delete();

        return redirect()
            ->route('hadiah.index')
            ->with('success', 'Hadiah berhasil dihapus.');
    }
}
