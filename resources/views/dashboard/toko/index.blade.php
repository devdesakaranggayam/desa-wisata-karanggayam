@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Toko</h5>
            <a href="{{ route('toko.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Tambah Toko
            </a>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($toko as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->no_hp }}</td>
                        <td>{{ $row->alamat }}</td>
                        <td>
                            <a href="{{ route('toko.edit', $row->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('toko.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
</script>
@endpush
