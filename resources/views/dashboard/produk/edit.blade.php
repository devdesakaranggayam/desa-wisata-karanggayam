@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Produk</h5>
        <a href="{{ route('produk.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
    
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama" name="nama" 
                       value="{{ old('nama', $produk->nama) }}" required>
            </div>
    
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" step="0.01" class="form-control" id="harga" name="harga" 
                       value="{{ old('harga', $produk->harga) }}" required>
            </div>
    
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="summernote" name="deskripsi" id="deskripsi">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            </div>
    
            {{-- Galeri file lama --}}
            <div class="mb-3">
                <label class="form-label">Galeri Produk</label>
                <div class="row" id="image-preview-container">
                    @foreach($produk->files as $file)
                        <div class="col-md-3 mb-3" id="file-{{$file->id}}">
                            <div class="card h-100 shadow-sm position-relative">
                                <div class="img-wrapper">
                                    <img src="{{ asset('storage/' . $file->path) }}" 
                                            class="card-img-top" 
                                            alt="{{ $file->nama ?? 'Gambar Produk' }}">
                                </div>
                                <button type="button" 
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-file" 
                                        data-produk-id="{{ $produk->id }}"
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
    
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Update
            </button>
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .img-wrapper {
        width: 100%;
        height: 200px; /* tinggi seragam */
        overflow: hidden;
        border-top-left-radius: .375rem;
        border-top-right-radius: .375rem;
        background: #f8f9fa; /* fallback buat non-image */
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
    $(".remove-file").click(function () {
        let fileId = $(this).data("id");
        let produkId = $(this).data("produk-id");
        let el = $("#file-" + fileId);

        if (!confirm("Apakah anda yakin untuk menghapus file ini?")) return;

        $.ajax({
            url: `/produk/${produkId}/file/${fileId}`,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content")
            },
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    el.remove();
                } else {
                    toastr.success('File berhasil dihapus')
                }
            },
            error: function () {
                toastr.error('Gagal menghapus file')
            }
        });
    });
});
</script>
@endpush
