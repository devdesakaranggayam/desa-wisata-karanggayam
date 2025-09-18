@extends('layouts.app')

@section('title', 'Detail Carousel')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Carousel</h5>
                <a href="{{ route('carousel.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span>Nama:</span>
                    <span>{{ $carousel->nama }}</span>
                </div>
                
                @if ($carousel->files->isNotEmpty())
                    <div class="row">
                        @foreach ($carousel->files->sortBy('urutan') as $file)
                            <div class="col-md-3 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('storage/' . $file->path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $file->nama ?? 'Gambar Carousel' }}">
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        <p class="card-text mb-1">
                                            Urutan: <strong>{{ $file->urutan ?? '-' }}</strong>
                                        </p>
                                        <p class="card-text text-truncate mb-0">
                                            {{ $file->nama ?? 'Gambar' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Belum ada gambar carousel.</p>
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
