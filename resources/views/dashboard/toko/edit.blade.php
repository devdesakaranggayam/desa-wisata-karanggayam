@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Edit Toko</h4>

        <form action="{{ route('toko.update', $toko->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Nama Toko</label>
                <input type="text" name="nama" class="form-control" value="{{ $toko->nama }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ $toko->no_hp }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea type="text" name="alamat" class="form-control" required>{{ $toko->alamat }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('toko.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
