@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-primary">Dashboard Pengawas</h2>
            <p class="text-secondary">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Laporan</h5>
                    <h2 class="fw-bold">{{ $riwayatPengawasan->count() }}</h2>
                    <p class="card-text small">Laporan yang telah Anda kirim.</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-primary h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-primary fw-bold">Buat Laporan Baru</h5>
                        <p class="card-text text-muted">Input surat tugas, notulen, dan dokumentasi lapangan.</p>
                    </div>
                    <a href="{{ route('pengawasan.create') }}" class="btn btn-lg btn-primary shadow">
                        + Buat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Riwayat Laporan & Surat Tugas</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Surat / Kegiatan</th>
                            <th>Lokasi</th>
                            <th>Aksi (Download)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPengawasan as $riwayat)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($riwayat->tanggal_kegiatan)->format('d/m/Y') }}</div>
                                <small class="text-muted">Input: {{ $riwayat->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $riwayat->nomor_surat }}</div>
                                <small>{{ Str::limit($riwayat->judul_kegiatan, 40) }}</small>
                            </td>
                            <td>
                                {{ $riwayat->desa_kelurahan }},<br>
                                <small class="text-muted">Kec. {{ $riwayat->kecamatan }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    {{-- 1. SCAN SURAT TUGAS --}}
                                    @if($riwayat->scan_surat_tugas_path)
                                        <a href="{{ asset('storage/' . $riwayat->scan_surat_tugas_path) }}" download class="btn btn-sm btn-outline-dark" title="Lihat Scan Surat">
                                            📄 Scan
                                        </a>
                                    @endif

                                    {{-- 2. NOTULEN --}}
                                    <a href="{{ route('pengawasan.cetak.notulen', $riwayat->id) }}" target="_blank" class="btn btn-sm btn-warning" title="Cetak Notulen">
                                        📝 Notulen
                                    </a>

                                    {{-- 3. DOKUMENTASI --}}
                                    <a href="{{ route('pengawasan.cetak.dokumentasi', $riwayat->id) }}" target="_blank" class="btn btn-sm btn-info text-white" title="Cetak Dokumentasi">
                                        📷 Foto
                                    </a>

                                    {{-- TOMBOL DETAIL --}}
                                    <a href="{{ route('pengawasan.detail', $riwayat->id) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                        🔍
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <img src="https://img.icons8.com/ios/100/cccccc/empty-box.png" alt="Kosong" style="width: 60px; opacity: 0.5;">
                                <p class="text-muted mt-3">Belum ada laporan yang dibuat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection