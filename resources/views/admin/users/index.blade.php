@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">Manajemen Pengguna</h3>
            <p class="text-secondary small mb-0">Kelola akun pegawai dan hak akses.</p>
        </div>
        <div>
            <a href="{{ route('dashboard.admin') }}" class="btn btn-outline-secondary me-2">
                &larr; Kembali
            </a>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                + Tambah Pegawai
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Role</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nip }}</td>
                        <td>
                            @if($user->role == 'admin') <span class="badge bg-danger">Admin</span>
                            @elseif($user->role == 'ketua') <span class="badge bg-primary">Ketua</span>
                            @else <span class="badge bg-success">Pengawas</span>
                            @endif
                        </td>
                        <td>{{ $user->jabatan }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection