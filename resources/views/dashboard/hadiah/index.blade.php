@extends('layouts.app')

@section('title', 'Daftar Hadiah')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Hadiah</h5>
        <a href="{{ route('hadiah.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Tambah Hadiah
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="datatable table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Minimal Stamp</th>
                        <th>Thumbnail</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hadiah as $h)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $h->nama }}</td>
                        <td>{!! Str::limit(strip_tags($h->deskripsi), 50) !!}</td>
                        <td>{{ $h->min_stamp }}</td>
                        <td>
                            @if($h->thumbnail)
                                <img src="{{ asset('storage/' . $h->thumbnail->path) }}" 
                                     alt="thumbnail" class="img-thumbnail" width="60">
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('hadiah.edit',$h->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('hadiah.destroy',$h->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus hadiah ini?')">
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
