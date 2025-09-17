@extends('layouts.app')

@section('title', 'Tambah Titik Wisata')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Titik Wisata Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('wisata.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Titik Wisata</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="summernote" name="deskripsi" id="deskripsi"></textarea>
            </div>

            <div class="mb-3">
                <label for="files" class="form-label">Upload File (Bisa Banyak)</label>
                <input type="file" class="form-control" id="files" name="files[]" multiple>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <a href="{{ route('wisata.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
