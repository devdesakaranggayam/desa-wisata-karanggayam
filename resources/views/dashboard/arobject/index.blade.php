@extends('layouts.app')

@section('title', 'Daftar AR Object')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar AR Object</h5>
        <a href="{{ route('arobject.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Tambah AR Object
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="datatable table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Audio</th>
                        <th>Model 3D</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arobjects as $obj)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $obj->judul }}</td>
                        <td>{!! Str::limit(strip_tags($obj->deskripsi), 50) !!}</td>
                        <td>
                            @if($obj->audio_path)
                                <a href="{{ asset('storage/'.$obj->audio_path) }}" target="_blank" class="badge bg-success">
                                    <i class="fa fa-volume-up"></i> Audio
                                </a>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            @if($obj->{'3d_path'})
                                <a href="{{ asset('storage/'.$obj->{'3d_path'}) }}" target="_blank" class="badge bg-info">
                                    <i class="fa fa-cube"></i> Model
                                </a>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('arobject.edit',$obj->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{ route('arobject.qr', $obj->id) }}" 
                                target="_blank" 
                                class="btn btn-sm btn-primary">
                                <i class="fa fa-qrcode"></i>
                            </a>

                            <form action="{{ route('arobject.destroy',$obj->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">
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
