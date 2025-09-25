@extends('layouts.app')

@section('title', 'Daftar Game Stamps')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Game Stamps</h5>
        <a href="{{ route('game-stamps.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Tambah Stamp
        </a>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table id="datatable" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Icon</th>
                        <th>Jenis</th>
                        <th>Skor Minimum</th>
                        <th>Koordinat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stamps as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>
                            @if($s->icon_path)
                                <img src="{{ asset('storage/'.$s->icon_path) }}" alt="icon" width="40" height="40">
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>{{ ucfirst($s->type) }}</td>
                        <td>{{ $s->passing_score }}</td>
                        <td>({{ $s->x ?? '-' }}, {{ $s->y ?? '-' }})</td>
                        <td>
                            <a href="{{ route('game-stamps.edit',$s->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('game-stamps.destroy',$s->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus stamp ini?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
