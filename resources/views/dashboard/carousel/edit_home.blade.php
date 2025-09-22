@extends('layouts.app')

@section('title', 'Edit Carousel')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Carousel</h5>
                <a href="{{ route('carousel.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{ route('carousel.update', $carousel->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Carousel --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Carousel</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $carousel->nama) }}" class="form-control" required>
                    </div>

                    {{-- File Lama --}}
                    <div class="mb-3">
                        <label class="form-label">File Saat ini</label>
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $carousel->files->first()->path) }}" 
                                class="img-fluid rounded shadow-sm" 
                                style="width:300px; height:auto;"
                                alt="{{ $carousel->files->first()->nama ?? 'Gambar Carousel' }}">
                        </div>
                    </div>

                    {{-- Upload Baru --}}
                    <div class="mb-3">
                        <label class="form-label">Upload File Baru</label>
                        <input type="file" name="file" class="form-control mb-2">

                        <input type="hidden" name="urutan" class="form-control" value="{{ old('urutan', $carousel->files->first()->urutan ?? 0) }}">
                        <small class="text-muted d-block mt-2">File baru akan menggantikan file lama jika diisi.</small>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                    <a href="{{ route('carousel.index') }}" class="btn btn-secondary">Batal</a>
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
        max-height: 250px;
        overflow: hidden;
        border-radius: .375rem;
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush
