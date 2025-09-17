<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WisataController extends Controller
{
    public function index()
    {
        $wisata = Wisata::with('files')->get();
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
            'files.*' => 'nullable|file|max:2048',
        ]);

        $wisata = Wisata::create($request->only(['nama', 'deskripsi']));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('wisata', 'public');
                $wisata->files()->create([
                    'nama' => $file->getClientOriginalName(),
                    'tipe_file' => $file->getClientMimeType(),
                    'path' => $path,
                ]);
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
            'files.*' => 'nullable|file|max:2048',
        ]);

        $wisata = Wisata::findOrFail($id);
        $wisata->update($request->only(['nama', 'deskripsi']));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('wisata', 'public');
                $wisata->files()->create([
                    'nama' => $file->getClientOriginalName(),
                    'tipe_file' => $file->getClientMimeType(),
                    'path' => $path,
                ]);
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
