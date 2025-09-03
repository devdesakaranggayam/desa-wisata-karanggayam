@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Tambah Toko</h4>
    
        <form action="{{ route('toko.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Toko</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>
    
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('toko.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
