@extends('layouts.app')

@section('title', 'Edit Kesenian')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Kesenian</h5>
                <a href="{{ route('kesenian.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('kesenian.update', $kesenian->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kesenian</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $kesenian->nama) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="summernote" name="deskripsi" id="deskripsi">{{ old('deskripsi', $kesenian->deskripsi) }}</textarea>
                    </div>

                    {{-- Galeri --}}
                    <div class="mb-3">
                        <label class="form-label">Galeri Kesenian</label>
                        <div class="row" id="image-preview-container">
                            @foreach($kesenian->files as $file)
                                <div class="col-md-3 mb-3" id="file-{{$file->id}}">
                                    <div class="card h-100 shadow-sm position-relative">
                                        <div class="img-wrapper">
                                            <img src="{{ asset('storage/' . $file->path) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $file->nama ?? 'Gambar Kesenian' }}">
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-file" 
                                                data-kesenian-id="{{ $kesenian->id }}"
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
                        <label for="files" class="form-label">Tambah File Baru</label>
                        <input type="file" class="form-control" id="files" name="files[]" multiple>
                        <small class="text-muted">File baru akan ditambahkan, file lama tidak otomatis terhapus.</small>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                    <a href="{{ route('kesenian.index') }}" class="btn btn-secondary">Batal</a>
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
    $(document).ready(function () {
        // tombol hapus ditekan
        $(document).on('click', '.remove-file', function (e) {
            e.preventDefault();

            if (!confirm("Apakah anda yakin untuk menghapus file ini?")) return;

            let fileId = $(this).data('file-id');
            let kesenianId = $(this).data('kesenian-id');

            $.ajax({
                url: `/kesenian/${kesenianId}/file/${fileId}`,
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

