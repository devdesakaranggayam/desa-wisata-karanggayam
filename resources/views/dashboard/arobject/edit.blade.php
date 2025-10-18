@extends('layouts.app')

@section('title', 'Edit AR Object')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit AR Object</h5>
        <a href="{{ route('arobject.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('arobject.update', $arobject->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" 
                       value="{{ old('judul', $arobject->judul) }}" required>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="summernote" name="deskripsi" id="deskripsi">{{ old('deskripsi', $arobject->deskripsi) }}</textarea>
            </div>

            {{-- Audio lama --}}
            <div class="mb-3">
                <label class="form-label">Audio Saat Ini</label><br>
                @if($arobject->audio_path)
                    <div class="file-container">
                        <audio controls class="mt-2 mb-2" style="max-width: 300px;">
                            <source src="{{ asset('storage/'.$arobject->audio_path) }}" type="audio/mpeg">
                            Browser Anda tidak mendukung pemutar audio.
                        </audio>
                        <div>
                            <button type="button" 
                                    class="btn btn-sm btn-danger remove-existing-file" 
                                    data-type="audio"
                                    data-id="{{ $arobject->id }}">
                                <i class="fa fa-trash"></i> Hapus Audio
                            </button>
                        </div>
                    </div>
                @else
                    <span class="badge bg-secondary">Tidak ada audio</span>
                @endif
            </div>

            {{-- Model 3D lama --}}
            <div class="mb-3">
                <label class="form-label">Model 3D Saat Ini</label><br>
                @if($arobject->{'3d_path'})
                    <div class="file-container">
                        <a href="{{ asset('storage/'.$arobject->{'3d_path'}) }}" 
                           target="_blank" class="badge bg-info mb-2 d-inline-block">
                            <i class="fa fa-cube"></i> Lihat File 3D
                        </a>
                        <div>
                            <button type="button" 
                                    class="btn btn-sm btn-danger remove-existing-file" 
                                    data-type="model3d"
                                    data-id="{{ $arobject->id }}">
                                <i class="fa fa-trash"></i> Hapus Model
                            </button>
                        </div>
                    </div>
                @else
                    <span class="badge bg-secondary">Tidak ada file 3D</span>
                @endif
            </div>

            {{-- Upload baru --}}
            <div class="mb-3">
                <label class="form-label">Ganti Audio / Model 3D</label>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Upload Audio (mp3/wav)</label>
                        <input type="file" name="audio" class="form-control" accept=".mp3,.wav">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload Model 3D (.glb)</label>
                        <input type="file" name="model3d" class="form-control" accept=".glb">
                    </div>
                </div>
                <small class="text-muted d-block mt-2">
                    Jika tidak diisi, file lama tetap digunakan.
                </small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Update
            </button>
            <a href="{{ route('arobject.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    // Hapus file lama (AJAX)
    $(".remove-existing-file").click(function () {
        let id = $(this).data("id");
        let type = $(this).data("type");
        let el = $(this);

        if (!confirm("Yakin hapus file ini?")) return;

        $.ajax({
            url: `/arobject/${id}/remove-file`,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                type: type
            },
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    toastr.success('File berhasil dihapus');
                    el.closest('.file-container').remove();
                } else {
                    toastr.error('Gagal menghapus file');
                }
            },
            error: function () {
                toastr.error('Terjadi kesalahan saat menghapus file');
            }
        });
    });
});
</script>
@endpush
