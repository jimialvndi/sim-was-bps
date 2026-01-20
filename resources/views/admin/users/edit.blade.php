@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark fw-bold">Edit Data Pengguna</div>

                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label>NIP</label>
                            <input type="number" name="nip" class="form-control" value="{{ $user->nip }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Password (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                        </div>

                        <div class="mb-3">
                            <label>Role / Hak Akses</label>
                            <select name="role" class="form-control" required>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Ketua Tim Pertanian) - Full Akses</option>
                                <option value="ketua" {{ $user->role == 'ketua' ? 'selected' : '' }}>Viewer (Kepala BPS / Kasubbag) - Hanya Lihat</option>
                                <option value="pengawas" {{ $user->role == 'pengawas' ? 'selected' : '' }}>User (Pengawas Lapangan) - Input Laporan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $user->jabatan }}" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection