@extends('layouts.app')

@section('title', 'Tambah AR Object')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah AR Object Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('arobject.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="summernote" name="deskripsi" id="deskripsi"></textarea>
            </div>

            <div class="mb-3">
                <label for="audio" class="form-label">Audio</label>
                <input type="file" class="form-control" id="audio" name="audio" accept=".mp3,.wav" required>
                <small class="text-muted">Format: mp3, wav</small>
            </div>

            <div class="mb-3">
                <label for="model3d" class="form-label">File 3D (GLB)</label>
                <input type="file" class="form-control" id="model3d" name="model3d" accept=".glb" required>
                <small class="text-muted">Format: .glb</small>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <a href="{{ route('arobject.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
