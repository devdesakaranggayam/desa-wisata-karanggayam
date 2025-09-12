@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Tambah Admin</h4>
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('admin.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
            
                    <div class="mb-3">
                        <label  for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required></input>
                    </div>

                    <div class="mb-3">
                        <label for="nomor_hp" class="form-label">No HP</label>
                        <input type="text" name="nomor_hp" placeholder="Cth: 6281234567890" class="form-control" required>
                    </div>
        
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
            
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
