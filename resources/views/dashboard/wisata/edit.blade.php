@extends('layouts.app')

@section('title', 'Edit Titik Wisata')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Titik Wisata</h5>
                <a href="{{ route('wisata.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('wisata.update', $wisata->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Titik Wisata</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $wisata->nama) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="summernote" name="deskripsi" id="deskripsi">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                    </div>

                    {{-- Galeri --}}
                    <div class="mb-3">
                        <label class="form-label">Galeri Titik Wisata</label>
                        <div class="row" id="image-preview-container">
                            @foreach($wisata->files as $file)
                                <div class="col-md-3 mb-3" id="file-{{$file->id}}">
                                    <div class="card h-100 shadow-sm position-relative">
                                        <div class="img-wrapper">
                                            <img src="{{ asset('storage/' . $file->path) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $file->nama ?? 'Gambar Titik Wisata' }}">
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-existing-file" 
                                                data-titik-id="{{ $wisata->id }}"
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

                    <div class="mb-3">
                        <label class="form-label">Tambah File</label>
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
                        <small class="text-muted d-block mt-2">File baru akan ditambahkan, file lama tidak otomatis terhapus.</small>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                    <a href="{{ route('wisata.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .img-wrapper {
        width: 100%;
        height: 200px;
        overflow: hidden;
        border-top-left-radius: .375rem;
        border-top-right-radius: .375rem;
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .img-wrapper img:hover {
        transform: scale(1.1);
    }
</style>
@endpush


@push('scripts')
<script>
$(function () {
    let fileIndex = 1;

    // Tambah input file baru
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

    $(document).on('click', '.remove-file', function () {
        $(this).closest('.file-group').remove();
    });

    $(document).on('click', '.remove-existing-file', function (e) {
        e.preventDefault();

        if (!confirm("Apakah anda yakin untuk menghapus file ini?")) return;

        let fileId = $(this).data('file-id');
        let titikId = $(this).data('titik-id');

        $.ajax({
            url: `/wisata/${titikId}/file/${fileId}`,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $(`#file-${fileId}`).remove(); // hapus element dari DOM
                    toastr.success('File berhasil dihapus')
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                toastr.error('Gagal menghapus file')
            }
        });
    });
});
</script>
@endpush