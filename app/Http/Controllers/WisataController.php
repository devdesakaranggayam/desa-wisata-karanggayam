<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Wisata;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WisataController extends Controller
{
    public function index()
    {
        $wisata = Wisata::wisata()->with('files')->get();
        return view('dashboard.wisata.index', compact('wisata'));
    }

    public function create()
    {
        return view('dashboard.wisata.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        $data = $request->only(['nama', 'deskripsi']);
        $data['type'] = "wisata";
        $wisata = Wisata::create($data);

        if ($request->has('files')) {
            foreach ($request->input('files') as $index => $fileInput) {
                $uploadedFile = $request->file("files.$index.file");
                if ($uploadedFile) {
                    $path = $uploadedFile->store('wisata', 'public');
                    $ext = $uploadedFile->getClientOriginalExtension();
                    $wisata->files()->create([
                        'nama'      => Str::random(20) . '.' . $ext,
                        'path'      => $path,
                        'tipe_file' => $uploadedFile->getClientMimeType(),
                        'urutan'    => $fileInput['urutan'] ?? $index,
                    ]);
                }
            }
        }

        return redirect()->route('wisata.index')
            ->with('success', success_msg('insert'));
    }

    public function show($id)
    {
        $wisata = Wisata::with('files')->findOrFail($id);
        return view('dashboard.wisata.detail', compact('wisata'));
    }

    public function edit($id)
    {
        $wisata = Wisata::with('files')->findOrFail($id);
        return view('dashboard.wisata.edit', compact('wisata'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $wisata = Wisata::findOrFail($id);
        $wisata["type"] = "wisata";
        $wisata->update($request->only(['nama', 'deskripsi']));

        if ($request->has('files')) {
            foreach ($request->input('files') as $index => $fileInput) {
                $uploadedFile = $request->file("files.$index.file");
                if ($uploadedFile) {
                    $path = $uploadedFile->store('wisata', 'public');
                    $ext = $uploadedFile->getClientOriginalExtension();
                    $wisata->files()->create([
                        'nama'      => Str::random(20) . '.' . $ext,
                        'path'      => $path,
                        'tipe_file' => $uploadedFile->getClientMimeType(),
                        'urutan'    => $fileInput['urutan'] ?? $index,
                    ]);
                }
            }
        }

        return redirect()->route('wisata.index')
            ->with('success', success_msg('update'));
    }

    public function destroy($id)
    {
        $wisata = Wisata::with('files')->findOrFail($id);

        // hapus file dari storage
        foreach ($wisata->files as $file) {
            Storage::delete($file->path);
            $file->delete();
        }

        $wisata->delete();

        return redirect()->route('wisata.index')
            ->with('success', success_msg('delete'));
    }

    public function removeFile(Wisata $wisata, File $file)
    {
        // cek kalau file memang milik wisata ini
        if ($file->fileable_id !== $wisata->id) {
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
