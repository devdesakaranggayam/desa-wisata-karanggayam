<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\GameStamp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\GameStampType;

class GameStampController extends Controller
{
    public function __construct()
    {
        if (!is_dev()) {
            abort(404);
        }
    }
    
    public function index()
    {
        $stamps = GameStamp::all();
        return view('dashboard.game-stamp.index', compact('stamps'));
    }

    public function create()
    {
        $types = GameStampType::all();
        return view('dashboard.game-stamp.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'                   => 'required|string|max:255',
            'icon_path'              => 'required|image|mimes:png,jpg,jpeg,svg',
            'x'                      => 'required|integer',
            'y'                      => 'required|integer',
            'questions.*.question_text'         => 'required|string',
            'questions.*.thumbnail_path'        => 'required|image|mimes:png,jpg,jpeg',
            'questions.*.answers.*.answer_text' => 'required|string',
            'questions.*.answers.*.is_correct'  => 'nullable|boolean',
        ]);

        // Upload icon
        $iconFile = $request->file('icon_path');
        $iconName = Str::random(10) . '.' . $iconFile->getClientOriginalExtension();
        $iconPath = $iconFile->storeAs('game/icons', $iconName, 'public');

        // Simpan GameStamp
        $gameStamp = GameStamp::create([
            'nama' => $request->nama,
            'icon_path' => $iconPath,
            'x' => $request->x,
            'y' => $request->y,
            'type' => $request->type,
            'passing_score' => $request->passing_score
        ]);

        // Simpan pertanyaan + jawaban
        if ($request->has('questions')) {
            foreach ($request->questions as $q) {
                $thumbFile = $q['thumbnail_path'];
                $thumbName = Str::random(10) . '.' . $thumbFile->getClientOriginalExtension();
                $thumbPath = $thumbFile->storeAs('game/questions', $thumbName, 'public');

                $question = $gameStamp->questions()->create([
                    'question_text'  => $q['question_text'],
                    'thumbnail_path' => $thumbPath,
                ]);

                if (isset($q['answers'])) {
                    foreach ($q['answers'] as $a) {
                        $question->answers()->create([
                            'answer_text' => $a['answer_text'],
                            'is_correct'  => isset($a['is_correct']) ? 1 : 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('game-stamps.index')
                        ->with('success', 'Game Stamp berhasil ditambahkan.');
    }


    public function edit(GameStamp $gameStamp)
    {
        $types = GameStampType::all();
        return view('dashboard.game-stamp.edit', compact('gameStamp','types'));
    }

    public function update(Request $request, GameStamp $gameStamp)
    {
        $request->validate([
            'nama'                   => 'required|string|max:255',
            'icon_path'              => 'nullable|image|mimes:png,jpg,jpeg,svg',
            'x'                      => 'required|integer',
            'y'                      => 'required|integer',
            'questions.*.question_text'         => 'required|string',
            'questions.*.thumbnail_path'        => 'nullable|image|mimes:png,jpg,jpeg',
            'questions.*.answers.*.answer_text' => 'required|string',
            'questions.*.answers.*.is_correct'  => 'nullable|boolean',
        ]);

        // Update icon kalau ada file baru
        if ($request->hasFile('icon_path')) {
            if ($gameStamp->icon_path && Storage::disk('public')->exists($gameStamp->icon_path)) {
                Storage::disk('public')->delete($gameStamp->icon_path);
            }
            $iconFile = $request->file('icon_path');
            $iconName = Str::random(10) . '.' . $iconFile->getClientOriginalExtension();
            $iconPath = $iconFile->storeAs('game/icons', $iconName, 'public');
            $gameStamp->icon_path = $iconPath;
        }

        $gameStamp->nama = $request->nama;
        $gameStamp->x = $request->x;
        $gameStamp->y = $request->y;
        $gameStamp->type = $request->type;
        $gameStamp->passing_score = $request->passing_score;
        
        $gameStamp->save();

        // Reset pertanyaan lama + jawaban
        foreach ($gameStamp->questions as $oldQ) {
            if ($oldQ->thumbnail_path && Storage::disk('public')->exists($oldQ->thumbnail_path)) {
                Storage::disk('public')->delete($oldQ->thumbnail_path);
            }
            $oldQ->answers()->delete();
            $oldQ->delete();
        }

        // Simpan pertanyaan baru
        if ($request->has('questions')) {
            foreach ($request->questions as $q) {
                $thumbPath = null;
                if (isset($q['thumbnail_path']) && $q['thumbnail_path'] instanceof \Illuminate\Http\UploadedFile) {
                    $thumbFile = $q['thumbnail_path'];
                    $thumbName = Str::random(10) . '.' . $thumbFile->getClientOriginalExtension();
                    $thumbPath = $thumbFile->storeAs('game/questions', $thumbName, 'public');
                }

                $question = $gameStamp->questions()->create([
                    'question_text'  => $q['question_text'],
                    'thumbnail_path' => $thumbPath,
                ]);

                if (isset($q['answers'])) {
                    foreach ($q['answers'] as $a) {
                        $question->answers()->create([
                            'answer_text' => $a['answer_text'],
                            'is_correct'  => isset($a['is_correct']) ? 1 : 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('game-stamps.index')
                        ->with('success', 'Game Stamp berhasil diperbarui.');
    }



    public function destroy(GameStamp $gameStamp)
    {
        // Hapus relasi Answers → Questions → GameStamp
        foreach ($gameStamp->questions as $question) {
            $question->answers()->delete();
        }

        $gameStamp->questions()->delete();

        // Hapus file icon di storage jika ada
        if ($gameStamp->icon_path && \Storage::disk('public')->exists($gameStamp->icon_path)) {
            Storage::disk('public')->delete($gameStamp->icon_path);
        }

        // Hapus game stamp
        $gameStamp->delete();

        return redirect()->route('game-stamps.index')
                        ->with('success', 'Game stamp beserta pertanyaan & jawaban berhasil dihapus.');
    }

}
