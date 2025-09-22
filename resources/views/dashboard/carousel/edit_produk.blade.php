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
                        <label class="form-label">File Carousel</label>
                        <div class="row" id="image-preview-container">
                            @foreach($carousel->files as $file)
                                <div class="col-md-3 mb-3" id="file-{{$file->id}}">
                                    <div class="card h-100 shadow-sm position-relative">
                                        <div class="img-wrapper">
                                            <img src="{{ asset('storage/' . $file->path) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $file->nama ?? 'Gambar Carousel' }}">
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-file" 
                                                data-carousel-id="{{ $carousel->id }}"
                                                data-file-id="{{ $file->id }}">
                                            &times;
                                        </button>
                                        <div class="card-body p-2">
                                            <p class="card-text text-truncate mb-0">{{ $file->nama ?? 'Gambar' }}</p>
                                            <div>
                                                <small class="text-muted">Urutan: {{ $file->urutan ?? '-' }}</small>
                                            </div>
                                            <small class="text-muted">Produk: {{ $file->produk_id ?? '-' }}</small>
                                            @if($carousel->identifier === 'home_produk' && $file->produk)
                                                <div><small class="text-success">Produk: {{ $file->produk->nama }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Upload Baru --}}
                    <div class="mb-3">
                        <label class="form-label">Tambah File Baru</label>
                        <div id="file-wrapper">
                            <div class="file-group mb-3 row">
                                <div class="col-md-4">
                                    <input type="file" name="files[0][file]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="files[0][urutan]" class="form-control" placeholder="Urutan">
                                </div>
                                @if($carousel->identifier === 'home_produk')
                                    <div class="col-md-3">
                                        <select name="files[0][produk_id]" class="form-control">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($produk as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-file"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-file" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Tambah File
                        </button>
                        <small class="text-muted d-block mt-2">File baru akan ditambahkan, file lama tidak otomatis terhapus.</small>
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
        height: 200px;
        overflow: hidden;
        border-top-left-radius: .375rem;
        border-top-right-radius: .375rem;
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .img-wrapper img:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
    let fileIndex = 1;

    // Tambah input file baru
    $('#add-file').on('click', function () {
        let produkSelect = `
            @if($carousel->identifier === 'home_produk')
                <div class="col-md-3">
                    <select name="files[\${fileIndex}][produk_id]" class="form-control">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produk as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        `;

        $('#file-wrapper').append(`
            <div class="file-group mb-3 row">
                <div class="col-md-4">
                    <input type="file" name="files[\${fileIndex}][file]" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="number" name="files[\${fileIndex}][urutan]" class="form-control" placeholder="Urutan">
                </div>
                ${produkSelect}
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-file"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        `);
        fileIndex++;
    });

    // Hapus input file baru
    $(document).on('click', '.remove-file', function () {
        $(this).closest('.file-group').remove();
    });

    // Hapus file lama via AJAX
    $(document).on('click', '.remove-file[data-carousel-id]', function (e) {
        e.preventDefault();
        if (!confirm("Apakah anda yakin untuk menghapus file ini?")) return;

        let fileId = $(this).data('file-id');
        let carouselId = $(this).data('carousel-id');

        $.ajax({
            url: `/carousel/${carouselId}/file/${fileId}`,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $(`#file-${fileId}`).remove();
                    toastr.success('File berhasil dihapus');
                }
            },
            error: function () {
                toastr.error('Gagal menghapus file');
            }
        });
    });
</script>
@endpush
