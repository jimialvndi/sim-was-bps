@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h3>Kelola Surat Tugas</h3>
        <a href="{{ route('admin.surat-tugas.create') }}" class="btn btn-primary">+ Buat Surat Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Judul Kegiatan</th>
                        <th>Petugas</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suratTugas as $st)
                    <tr>
                        <td>{{ $st->nomor_surat }}</td>
                        <td>{{ $st->judul_tugas }}</td>
                        <td>{{ $st->user->name }}</td>
                        <td>{{ $st->tgl_mulai }} s/d {{ $st->tgl_selesai }}</td>
                        <td>
                            <form action="{{ route('admin.surat-tugas.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Hapus surat ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
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