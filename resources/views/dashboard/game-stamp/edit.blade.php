@extends('layouts.app')

@section('title', 'Edit Game Stamp')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Edit Game Stamp</h4>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('game-stamps.update', $gameStamp->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Data GameStamp --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" 
                               value="{{ old('nama', $gameStamp->nama) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="icon_path" class="form-label">Icon</label><br>
                        @if($gameStamp->icon_path)
                            <img src="{{ asset('storage/'.$gameStamp->icon_path) }}" alt="icon" height="60" class="mb-2">
                        @endif
                        <input type="file" name="icon_path" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="x" class="form-label">Koordinat X</label>
                            <input type="number" name="x" class="form-control" 
                                   value="{{ old('x', $gameStamp->x) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="y" class="form-label">Koordinat Y</label>
                            <input type="number" name="y" class="form-control" 
                                   value="{{ old('y', $gameStamp->y) }}" required>
                        </div>
                    </div>

                    <hr>
                    <h5>Daftar Pertanyaan</h5>
                    <div id="questions-wrapper">
                        @foreach($gameStamp->questions as $qIdx => $question)
                        <div class="card mb-3 question-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6>Pertanyaan</h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-question"><i class="fa fa-trash"></i></button>
                                </div>

                                <input type="hidden" name="questions[{{ $qIdx }}][id]" value="{{ $question->id }}">

                                <div class="mb-3">
                                    <input type="text" name="questions[{{ $qIdx }}][question_text]" 
                                           class="form-control" 
                                           value="{{ old("questions.$qIdx.question_text", $question->question_text) }}" 
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Thumbnail Pertanyaan</label><br>
                                    @if($question->thumbnail_path)
                                        <img src="{{ asset('storage/'.$question->thumbnail_path) }}" alt="thumb" height="60" class="mb-2">
                                    @endif
                                    <input type="file" name="questions[{{ $qIdx }}][thumbnail_path]" 
                                           class="form-control" accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                                </div>

                                <h6>Jawaban</h6>
                                <div class="answers-wrapper">
                                    @foreach($question->answers as $aIdx => $answer)
                                    <div class="input-group mb-2">
                                        <input type="hidden" name="questions[{{ $qIdx }}][answers][{{ $aIdx }}][id]" value="{{ $answer->id }}">
                                        <input type="text" name="questions[{{ $qIdx }}][answers][{{ $aIdx }}][answer_text]" 
                                               class="form-control" 
                                               value="{{ old("questions.$qIdx.answers.$aIdx.answer_text", $answer->answer_text) }}" 
                                               required>
                                        <div class="input-group-text">
                                            <input type="checkbox" name="questions[{{ $qIdx }}][answers][{{ $aIdx }}][is_correct]" 
                                                   value="1" {{ $answer->is_correct ? 'checked' : '' }}> Benar
                                        </div>
                                        <button type="button" class="btn btn-danger remove-answer"><i class="fa fa-times"></i></button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-answer" data-index="{{ $qIdx }}">
                                    <i class="fa fa-plus"></i> Tambah Jawaban
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-sm btn-success mb-3" id="add-question">
                        <i class="fa fa-plus"></i> Tambah Pertanyaan
                    </button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('game-stamps.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let questionIndex = {{ $gameStamp->questions->count() }};

// Tambah pertanyaan baru
$('#add-question').click(function() {
    let qHtml = `
    <div class="card mb-3 question-block">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Pertanyaan Baru</h6>
                <button type="button" class="btn btn-sm btn-danger remove-question"><i class="fa fa-trash"></i></button>
            </div>

            <div class="mb-3">
                <input type="text" name="questions[${questionIndex}][question_text]" 
                       class="form-control" placeholder="Tulis pertanyaan..." required>
            </div>

            <div class="mb-3">
                <label class="form-label">Thumbnail Pertanyaan</label>
                <input type="file" name="questions[${questionIndex}][thumbnail_path]" 
                       class="form-control" accept="image/*" required>
            </div>

            <h6>Jawaban</h6>
            <div class="answers-wrapper"></div>
            <button type="button" class="btn btn-sm btn-outline-primary add-answer" data-index="${questionIndex}">
                <i class="fa fa-plus"></i> Tambah Jawaban
            </button>
        </div>
    </div>`;
    
    $('#questions-wrapper').append(qHtml);
    questionIndex++;
});

// Hapus pertanyaan
$(document).on('click', '.remove-question', function() {
    $(this).closest('.question-block').remove();
});

// Tambah jawaban
$(document).on('click', '.add-answer', function() {
    let qIdx = $(this).data('index');
    let answersWrapper = $(this).siblings('.answers-wrapper');
    let answerCount = answersWrapper.children().length;

    let aHtml = `
    <div class="input-group mb-2">
        <input type="text" name="questions[${qIdx}][answers][${answerCount}][answer_text]" 
               class="form-control" placeholder="Tulis jawaban..." required>
        <div class="input-group-text">
            <input type="checkbox" name="questions[${qIdx}][answers][${answerCount}][is_correct]" value="1"> Benar
        </div>
        <button type="button" class="btn btn-danger remove-answer"><i class="fa fa-times"></i></button>
    </div>`;
    
    answersWrapper.append(aHtml);
});

// Hapus jawaban
$(document).on('click', '.remove-answer', function() {
    $(this).closest('.input-group').remove();
});
</script>
@endpush
