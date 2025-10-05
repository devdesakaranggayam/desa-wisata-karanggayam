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
                            <img src="{{ asset('storage/'.$gameStamp->icon_path) }}" alt="icon" height="60" class="mb-2 d-block">
                        @endif
                        <input type="file" name="icon_path" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Detail Stamp</label>
                        <textarea class="summernote" name="deskripsi" id="deskripsi">{{ old('deskripsi', $gameStamp->deskripsi) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe Stamp</label>
                                <select name="type" id="type" class="form-control" required>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" {{ old('type', $gameStamp->type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="passing_score" class="form-label">Skor Minimum</label>
                                <input type="number" name="passing_score" class="form-control" 
                                       value="{{ old('passing_score', $gameStamp->passing_score) }}" required>
                                <small class="text-muted">Jumlah minimal jawaban benar untuk mendapatkan stamp</small>
                            </div>
                        </div>
                    </div>

                    {{-- PHOTO TYPE FILES --}}
                    <div class="mb-3">
                        <label class="form-label">Galeri</label>
                        <div class="row" id="image-preview-container">
                            @foreach($gameStamp->files as $file)
                                <div class="col-md-3 mb-3" id="file-{{$file->id}}">
                                    <div class="card h-100 shadow-sm position-relative">
                                        <div class="img-wrapper">
                                            <img src="{{ asset('storage/' . $file->path) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $file->nama ?? 'Gambar Game' }}">
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-existing-file" 
                                                data-id="{{ $gameStamp->id }}"
                                                data-file-id="{{ $file->id }}">
                                            &times;
                                        </button>
                                        <div class="card-body p-2">
                                            <p class="card-text text-truncate mb-0">{{ $file->nama ?? 'Gambar' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-3 type-foto" style="{{ $gameStamp->type == \App\Constants\GameStampType::PHOTO ? '' : 'display:none;' }}">
                        <div class="mb-3 type-foto" style="display:none;">
                            <label class="form-label" for="file-wrapper">Tambah File</label>
                            <div id="file-wrapper">
                                <div class="file-group mb-3 row">
                                    <div class="col-md-4">
                                        <input type="file" name="files[0][file]" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="files[0][urutan]" class="form-control" placeholder="Urutan">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-file"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-file" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Tambah File
                            </button>
                        </div>
                    </div>

                    {{-- QUIZ TYPE QUESTIONS --}}
                    <div class="quiz-wrapper" style="{{ $gameStamp->type == \App\Constants\GameStampType::QUIZ ? '' : 'display:none;' }}">
                        <hr>
                        <h5>Daftar Pertanyaan</h5>
                        <div id="questions-wrapper">
                            @foreach($gameStamp->questions as $qIdx => $question)
                            <div class="card mb-3 question-block">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6>Pertanyaan</h6>
                                        <button type="button" class="btn btn-sm btn-danger remove-question">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>

                                    <input type="hidden" name="questions[{{ $qIdx }}][id]" value="{{ $question->id }}">

                                    <div class="mb-3">
                                        <input type="text" name="questions[{{ $qIdx }}][question_text]" 
                                               class="form-control" 
                                               value="{{ old("questions.$qIdx.question_text", $question->question_text) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Thumbnail Pertanyaan</label><br>
                                        @if($question->thumbnail_path)
                                            <img src="{{ asset('storage/'.$question->thumbnail_path) }}" alt="thumb" height="60" class="mb-2 d-block">
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
                                                   value="{{ old("questions.$qIdx.answers.$aIdx.answer_text", $answer->answer_text) }}" required>
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
                    </div>

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
    $(document).ready(function() {
        let questionIndex = {{ $gameStamp->questions->count() }};
        let fileIndex = {{ $gameStamp->files->count() }};

        // Add new question
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

        // Add answer
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

        // Remove answer/question/file
        $(document).on('click', '.remove-answer, .remove-question, .remove-file', function() {
            $(this).closest('.input-group, .question-block, .file-group').remove();
        });

        // Add file (photo type)
        $('#add-file').on('click', function () {
            $('#file-wrapper').append(`
                <div class="file-group mb-3 row">
                    <div class="col-md-4">
                        <input type="file" name="files[${fileIndex}][file]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="files[${fileIndex}][urutan]" class="form-control" placeholder="Urutan">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-file"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `);
            fileIndex++;
        });

        function toggleTypeSections() {
            if ($('#type').val() === "{{ \App\Constants\GameStampType::PHOTO }}") {
                $('.quiz-wrapper').hide();
                $('.type-foto').show();
            } else {
                $('.quiz-wrapper').show();
                $('.type-foto').hide();
            }
        }

        toggleTypeSections();
        $('#type').on('change', toggleTypeSections);


        $(document).on('click', '.remove-existing-file', function (e) {
            e.preventDefault();

            if (!confirm("Apakah Anda yakin ingin menghapus file ini?")) return;

            let fileId = $(this).data('file-id');
            let gameId = $(this).data('id');

            $.ajax({
                url: `/game-stamps/${gameId}/file/${fileId}`,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        $(`#file-${fileId}`).fadeOut(300, function () { $(this).remove(); });
                        toastr.success('File berhasil dihapus');
                    } else {
                        toastr.warning(res.message || 'Gagal menghapus file');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    toastr.error('Terjadi kesalahan saat menghapus file');
                }
            });
        });
    });
</script>
@endpush
