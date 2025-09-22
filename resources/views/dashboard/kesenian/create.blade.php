@extends('layouts.app')

@section('title', 'Tambah Kesenian')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Kesenian Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('kesenian.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kesenian</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
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
            <a href="{{ route('kesenian.index') }}" class="btn btn-secondary">Batal</a>
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