@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Produk Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="toko_id" class="form-label">Pilih Toko</label>
                <select class="form-control" id="toko_id" name="toko_id" required>
                    <option value="">-- Pilih Toko --</option>
                    @foreach($tokos as $toko)
                        <option value="{{ $toko->id }}">{{ $toko->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" step="0.01" class="form-control" id="harga" name="harga" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="summernote" name="deskripsi" id="deskripsi"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tambah File</label>
                <div id="file-wrapper">
                    <div class="file-group mb-3 row">
                        <div class="col-md-4">
                            <input type="file" name="files[0][file]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="files[0][urutan]" class="form-control" placeholder="Urutan">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-file"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-file" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Tambah File
                </button>
            </div>


            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let fileIndex = 1;

    // Tambah input file baru
    $('#add-file').on('click', function () {
        $('#file-wrapper').append(`
            <div class="file-group mb-3 row">
                <div class="col-md-4">
                    <input type="file" name="files[${fileIndex}][file]" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="number" name="files[${fileIndex}][urutan]" class="form-control" placeholder="Urutan">
                </div>
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

</script>
@endpush