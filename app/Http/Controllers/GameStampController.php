<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Question;
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
            'x' => $request->x ?? 0,
            'y' => $request->y ?? 0,
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
        $gameStamp->type = $request->type;
        $gameStamp->passing_score = $request->passing_score;
        
        $gameStamp->save();

        // update atau sync berdasarkan 'id'
        if ($request->has('questions')) {
            foreach ($request->questions as $q) {
                // Cek apakah ini pertanyaan lama (ada id)
                if (isset($q['id'])) {
                    $question = Question::find($q['id']);

                    if ($question) {
                        // handle thumbnail
                        $thumbPath = $question->thumbnail_path; // default pakai yang lama
                        if (isset($q['thumbnail_path']) && $q['thumbnail_path'] instanceof \Illuminate\Http\UploadedFile) {
                            // hapus file lama kalau ada
                            if ($thumbPath && Storage::disk('public')->exists($thumbPath)) {
                                Storage::disk('public')->delete($thumbPath);
                            }
                            // upload baru
                            $thumbFile = $q['thumbnail_path'];
                            $thumbName = Str::random(10) . '.' . $thumbFile->getClientOriginalExtension();
                            $thumbPath = $thumbFile->storeAs('game/questions', $thumbName, 'public');
                        }

                        // update pertanyaan
                        $question->update([
                            'question_text'  => $q['question_text'],
                            'thumbnail_path' => $thumbPath,
                        ]);

                        // reset jawaban lama
                        $question->answers()->delete();

                        // simpan jawaban baru
                        if (isset($q['answers'])) {
                            foreach ($q['answers'] as $a) {
                                $question->answers()->create([
                                    'answer_text' => $a['answer_text'],
                                    'is_correct'  => isset($a['is_correct']) ? 1 : 0,
                                ]);
                            }
                        }
                    }
                } else {
                    // pertanyaan baru
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
