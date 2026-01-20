@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card col-md-8 mx-auto">
        <div class="card-header fw-bold">Tambah Pegawai Baru</div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>NIP (Nomor Induk Pegawai)</label>
                        <input type="number" name="nip" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Staff IPDS" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email (Untuk Login)</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Role / Hak Akses</label>
                    <select name="role" class="form-control" required>
                        <option value="admin">Admin (Ketua Tim Pertanian) - Full Akses</option>

                        <option value="ketua">Viewer (Kepala BPS / Kasubbag) - Hanya Lihat</option>

                        <option value="pengawas">User (Pengawas Lapangan) - Input Laporan</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Simpan Data Pegawai</button>
            </form>
        </div>
    </div>
</div>
@endsection