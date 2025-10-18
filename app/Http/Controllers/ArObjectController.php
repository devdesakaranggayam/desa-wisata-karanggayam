<?php

namespace App\Http\Controllers;

use App\Models\ArObject;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ArObjectController extends Controller
{
    public function index()
    {
        $arobjects = ArObject::latest()->get();
        return view('dashboard.arobject.index', compact('arobjects'));
    }


    public function create()
    {
        return view('dashboard.arobject.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'audio' => 'required|file',
            'model3d' => 'required|file',
        ]);

        $data = $request->only(['judul', 'deskripsi']);

        // store audio file
        if ($request->hasFile('audio')) {
            $audioFile = $request->file('audio');
            $filename = Str::random(20) . '.' . $audioFile->getClientOriginalExtension();
            $path = $audioFile->storeAs('models', $filename, 'public');
            $data['audio_path'] = $path;
        }

        // store 3D model file
        if ($request->hasFile('model3d')) {
            $modelFile = $request->file('model3d');
            $filename = Str::random(20) . '.' . $modelFile->getClientOriginalExtension();
            $path = $modelFile->storeAs('models', $filename, 'public');
            $data['3d_path'] = $path;
        }

        ArObject::create($data);

        return redirect()->route('arobject.index')
            ->with('success', success_msg('insert'));
    }

    public function show($id)
    {
        $arobjects = ArObject::findOrFail($id);
        return view('dashboard.arobject.detail', compact('arobjec$arobjects'));
    }

    public function edit($id)
    {
        $arobject = ArObject::findOrFail($id);
        return view('dashboard.arobject.edit', compact('arobject'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'audio' => 'nullable|file',
            'model3d' => 'nullable|file',
        ]);

        $arObject = ArObject::findOrFail($id);
        $data = $request->only(['judul', 'deskripsi']);

        // update audio file
        if ($request->hasFile('audio')) {
            if ($arObject->audio_path && Storage::disk('public')->exists($arObject->audio_path)) {
                Storage::disk('public')->delete($arObject->audio_path);
            }

            $audioFile = $request->file('audio');
            $filename = Str::random(20) . '.' . $audioFile->getClientOriginalExtension();
            $path = $audioFile->storeAs('models', $filename, 'public');
            $data['audio_path'] = $path;
        }

        // update 3D model file
        if ($request->hasFile('model3d')) {
            if ($arObject->{'3d_path'} && Storage::disk('public')->exists($arObject->{'3d_path'})) {
                Storage::disk('public')->delete($arObject->{'3d_path'});
            }

            $modelFile = $request->file('model3d');
            $filename = Str::random(20) . '.' . $modelFile->getClientOriginalExtension();
            $path = $modelFile->storeAs('models', $filename, 'public');
            $data['3d_path'] = $path;
        }

        $arObject->update($data);

        return redirect()->route('arobject.index')
            ->with('success', success_msg('update'));
    }

    public function destroy($id)
    {
        $arObject = ArObject::findOrFail($id);

        if ($arObject->audio_path && Storage::disk('public')->exists($arObject->audio_path)) {
            Storage::disk('public')->delete($arObject->audio_path);
        }

        if ($arObject->{'3d_path'} && Storage::disk('public')->exists($arObject->{'3d_path'})) {
            Storage::disk('public')->delete($arObject->{'3d_path'});
        }

        $arObject->delete();

        return redirect()->route('arobject.index')
            ->with('success', success_msg('delete'));
    }

    public function removeFile(Request $request, $id)
    {
        $ar = ArObject::findOrFail($id);
        $type = $request->type;

        if ($type === 'audio' && $ar->audio_path) {
            Storage::disk('public')->delete($ar->audio_path);
            $ar->update(['audio_path' => null]);
        }

        if ($type === 'model3d' && $ar->{'3d_path'}) {
            Storage::disk('public')->delete($ar->{'3d_path'});
            $ar->update(['3d_path' => null]);
        }

        return response()->json(['success' => true]);
    }


    // public function showQr($id)
    // {
    //     $arobject = ArObject::findOrFail($id);

    //     // Bisa isi QR-nya dengan UUID atau URL API

    //     // Generate QR code PNG dan langsung kirim ke browser
    //     return response(
    //         QrCode::format('png')->size(250)->generate($id)
    //     )->header('Content-Type', 'image/png');
    // }


public function showQr($id)
{
    $arobject = Arobject::findOrFail($id);

    // Generate QR base64 (tanpa imagick, super ringan)
    $qrPng = 'data:image/png;base64,' . base64_encode(
        QrCode::format('png')->size(300)->margin(2)->generate($arobject->id)
    );

    return view('dashboard.arobject.printqr', compact('arobject', 'qrPng'));
}

}
