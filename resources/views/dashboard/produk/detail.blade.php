@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="row">
    <div class="col-md-12">
        {{-- Detail Produk --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Produk</h5>
                <a href="{{ route('produk.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Nama Produk:</strong>
                    <p>{{ $produk->nama }}</p>
                </div>
                <div class="mb-3">
                    <strong>Harga:</strong>
                    <p>Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                </div>
                <div class="mb-3">
                    <strong>Deskripsi:</strong>
                    <div class="card">
                        <div class="card-body">
                            {!! $produk->deskripsi !!}
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Toko:</strong>
                    <p>{{ $produk->toko->nama ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Galeri Produk --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Galeri Produk</h5>
            </div>
            <div class="card-body">
                @if ($produk->files->isNotEmpty())
                    <div class="row">
                        @foreach ($produk->files as $file)
                            <div class="col-md-3 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('storage/' . $file->path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $file->nama ?? 'Gambar Produk' }}">
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="card-text text-truncate mb-0">{{ $file->nama ?? 'Gambar' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Belum ada gambar produk.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .img-wrapper {
        width: 100%;
        height: 200px; /* samain tinggi */
        overflow: hidden;
        border-top-left-radius: .375rem;
        border-top-right-radius: .375rem;
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* biar fill & crop rapi */
        transition: transform 0.3s ease;
    }
    .img-wrapper img:hover {
        transform: scale(1.1); /* efek zoom saat hover */
    }
</style>
@endpush
