<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Carousel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::with('files')->latest()->get();
        return view('dashboard.carousel.index', compact('carousels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $carousel = Carousel::create([
            'nama' => $request->nama,
        ]);

        if ($request->has('files')) {
            foreach ($request->input('files') as $index => $fileInput) {
                $uploadedFile = $request->file("files.$index.file");
                if ($uploadedFile) {
                    $path = $uploadedFile->store('carousels', 'public');
                    $ext = $uploadedFile->getClientOriginalExtension();
                    $carousel->files()->create([
                        'nama'      => Str::random(20) . '.' . $ext,
                        'path'      => $path,
                        'tipe_file' => $uploadedFile->getClientMimeType(),
                        'urutan'    => $fileInput['urutan'] ?? $index,
                    ]);
                }
            }
        }


        return redirect()->route('carousel.index')->with('success', 'Carousel berhasil ditambahkan.');
    }

    public function show(Carousel $carousel)
    {
        $carousel->load('files');
        return view('dashboard.carousel.detail', compact('carousel'));
    }

    public function edit(Carousel $carousel)
    {
        return view('dashboard.carousel.edit', compact('carousel'));
    }

    public function update(Request $request, Carousel $carousel)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $carousel->update([
            'nama' => $request->nama,
        ]);

        if ($request->has('files')) {
            foreach ($request->input('files') as $index => $fileInput) {
                $uploadedFile = $request->file("files.$index.file");
                if ($uploadedFile) {
                    $path = $uploadedFile->store('carousels', 'public');
                    $ext = $uploadedFile->getClientOriginalExtension();
                    $carousel->files()->create([
                        'nama'      => Str::random(20) . '.' . $ext,
                        'path'      => $path,
                        'tipe_file' => $uploadedFile->getClientMimeType(),
                        'urutan'    => $fileInput['urutan'] ?? $index,
                    ]);
                }
            }
        }


        return redirect()->route('carousel.index')->with('success', 'Carousel berhasil diperbarui.');
    }

    public function destroy(Carousel $carousel)
    {
        foreach ($carousel->files as $file) {
            Storage::disk('public')->delete($file->path);
            $file->delete();
        }

        $carousel->delete();
        return redirect()->route('carousel.index')->with('success', 'Carousel berhasil dihapus.');
    }

    public function destroyFile(Carousel $carousel, File $file)
    {
        if ($file->fileable_id !== $carousel->id || $file->fileable_type !== Carousel::class) {
            return response()->json(['success' => false, 'message' => 'File tidak valid'], 403);
        }
    
        Storage::disk('public')->delete($file->path);
        $file->delete();
    
        return response()->json(['success' => true]);
    }
}
