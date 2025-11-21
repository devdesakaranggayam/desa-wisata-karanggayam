@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pengaturan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kunci</th>
                        <th>Nilai</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($configs as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $c->key }}</td>
                        <td>{{ $c->value }}</td>
                        <td>{{ $c->desc }}</td>
                        <td>
                            <a href="#" 
                            class="btn btn-sm btn-warning btn-edit"
                            data-id="{{ $c->id }}"
                            data-key="{{ $c->key }}"
                            data-value="{{ $c->value }}">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Config</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('configs.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="config_id">

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="config_key" class="form-label">Kunci</label>
                        <input type="text" class="form-control" name="key" id="config_key" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="config_value" class="form-label">Nilai</label>
                        <input type="text" class="form-control" name="value" id="config_value">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();

        // Ketika tombol edit diklik
        $('.btn-edit').on('click', function() {
            let id = $(this).data('id');
            let key = $(this).data('key');
            let value = $(this).data('value');

            $('#config_id').val(id);
            $('#config_key').val(key);
            $('#config_value').val(value);

            $('#editModal').modal('show');
        });

    });
</script>
@endpush
