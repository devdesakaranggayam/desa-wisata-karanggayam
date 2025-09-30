@extends('layouts.app')

@section('title', 'Tambah Hadiah')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Hadiah Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('hadiah.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Hadiah</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <div class="mb-3">
                <label for="min_stamp" class="form-label">Minimal Stamp</label>
                <input type="number" class="form-control" id="min_stamp" name="min_stamp" min="0" value="0" required>
                <small class="text-muted">Jumlah minimal stamp yang diperlukan untuk mendapatkan hadiah</small>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="summernote" name="deskripsi" id="deskripsi"></textarea>
            </div>

            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail</label>
                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                <small class="text-muted">Upload gambar thumbnail hadiah</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Simpan
            </button>
            <a href="{{ route('hadiah.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
