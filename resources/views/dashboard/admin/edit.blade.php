@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Edit Admin</h4>

        <form action="{{ route('admin.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $admin->nama }}" required>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="{{ $admin->username }}" required>
            </div>

            <div class="mb-3">
                <label  for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required></input>
            </div>

            <div class="mb-3">
                <label for="nomor_hp" class="form-label">No HP</label>
                <input type="text" name="nomor_hp" placeholder="Cth: 6281234567890" class="form-control" value="{{ $admin->nomor_hp }}" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control">
                <span><small>Kosongkan jika tidak ingin mengganti password</small></span>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
