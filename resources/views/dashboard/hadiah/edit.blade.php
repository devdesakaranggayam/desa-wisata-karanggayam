@extends('layouts.app')

@section('title', 'Edit Hadiah')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Hadiah</h5>
        <a href="{{ route('hadiah.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('hadiah.update', $hadiah->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Hadiah</label>
                <input type="text" class="form-control" id="nama" name="nama" 
                       value="{{ old('nama', $hadiah->nama) }}" required>
            </div>

            <div class="mb-3">
                <label for="min_stamp" class="form-label">Minimal Stamp</label>
                <input type="number" class="form-control" id="min_stamp" name="min_stamp" min="0"
                       value="{{ old('min_stamp', $hadiah->min_stamp) }}" required>
                <small class="text-muted">Jumlah minimal stamp yang diperlukan untuk mendapatkan hadiah</small>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="summernote" name="deskripsi" id="deskripsi">{{ old('deskripsi', $hadiah->deskripsi) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail</label>
                @if($hadiah->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $hadiah->thumbnail->path) }}" 
                             alt="Thumbnail" class="img-thumbnail" width="120">
                    </div>
                @endif
                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                <small class="text-muted">Upload untuk mengganti thumbnail hadiah</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Update
            </button>
            <a href="{{ route('hadiah.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
