<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Wisata;
use App\Models\Kesenian;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KesenianController extends Controller
{
    public function index()
    {
        $kesenian = Wisata::kesenian()->with('files')->latest()->get();
        return view('dashboard.kesenian.index', compact('kesenian'));
    }

    public function create()
    {
        return view('dashboard.kesenian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $data = $request->only(['nama', 'deskripsi']);
        $data["type"] = "kesenian";
        $kesenian = Wisata::create($data);

        if ($request->has('files')) {
            foreach ($request->input('files') as $index => $fileInput) {
                $uploadedFile = $request->file("files.$index.file");
                if ($uploadedFile) {
                    $path = $uploadedFile->store('produk', 'public');
                    $ext = $uploadedFile->getClientOriginalExtension();
                    $kesenian->files()->create([
                        'nama'      => Str::random(20) . '.' . $ext,
                        'path'      => $path,
                        'tipe_file' => $uploadedFile->getClientMimeType(),
                        'urutan'    => $fileInput['urutan'] ?? $index,
                    ]);
                }
            }
        }

        return redirect()->route('kesenian.index')
            ->with('success', success_msg('insert'));
    }

    public function show($id)
    {
        $kesenian = Wisata::with('files')->findOrFail($id);
        return view('dashboard.kesenian.detail', compact('kesenian'));
    }

    public function edit($id)
    {
        $kesenian = Wisata::with('files')->findOrFail($id);
        return view('dashboard.kesenian.edit', compact('kesenian'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kesenian = Wisata::findOrFail($id);
        $kesenian->update($request->only(['nama', 'deskripsi']));

        if ($request->has('files')) {
            foreach ($request->input('files') as $index => $fileInput) {
                $uploadedFile = $request->file("files.$index.file");
                if ($uploadedFile) {
                    $path = $uploadedFile->store('kesenian', 'public');
                    $ext = $uploadedFile->getClientOriginalExtension();
                    $kesenian->files()->create([
                        'nama'      => Str::random(20) . '.' . $ext,
                        'path'      => $path,
                        'tipe_file' => $uploadedFile->getClientMimeType(),
                        'urutan'    => $fileInput['urutan'] ?? $index,
                    ]);
                }
            }
        }

        return redirect()->route('kesenian.index')
            ->with('success', success_msg('update'));
    }

    public function destroy($id)
    {
        $kesenian = Wisata::with('files')->findOrFail($id);

        // hapus file dari storage
        foreach ($kesenian->files as $file) {
            Storage::delete($file->path);
            $file->delete();
        }

        $kesenian->delete();

        return redirect()->route('kesenian.index')
            ->with('success', success_msg('delete'));
    }

    public function removeFile(Kesenian $kesenian, File $file)
    {
        // cek kalau file memang milik kesenian ini
        if ($file->fileable_id !== $kesenian->id) {
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
