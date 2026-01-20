@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card col-md-8 mx-auto">
        <div class="card-header">Buat Surat Tugas Baru</div>
        <div class="card-body">
            <form action="{{ route('admin.surat-tugas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nomor Surat</label>
                    <input type="text" name="nomor_surat" class="form-control" placeholder="Nomor: B-XXX/BPS/..." required>
                </div>
                <div class="mb-3">
                    <label>Judul Kegiatan</label>
                    <input type="text" name="judul_tugas" class="form-control" placeholder="Contoh: Survei Ubinan 2026" required>
                </div>
                <div class="mb-3">
                    <label>Pilih Petugas (Pengawas)</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Pilih Pengawas --</option>
                        @foreach($pengawas as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->nip }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Surat Tugas</button>
                <a href="{{ route('admin.surat-tugas.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection