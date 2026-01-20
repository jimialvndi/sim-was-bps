@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">Dashboard Administrator</h2>
            <p class="text-secondary">Monitoring seluruh kegiatan pengawasan lapangan.</p>
        </div>
        <div>
            <h2 class="fw-bold text-primary">Dashboard Monitoring</h2>

            <p class="text-secondary">
                Selamat Datang,
                @if(Auth::user()->role == 'ketua')
                Bapak/Ibu Pimpinan (Viewer Mode)
                @else
                Admin (Ketua Tim Pertanian)
                @endif
            </p>
        </div>

        <div>
            @if(Auth::user()->role == 'admin')
            <a href="{{ route('admin.users.index') }}" class="btn btn-dark">
                <i class="bi bi-people"></i> Kelola User / Pegawai
            </a>
            @endif
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <h3>{{ $totalLaporan }}</h3>
                    <div class="small">Total Laporan Masuk</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <h3>{{ $totalPengawas }}</h3>
                    <div class="small">Pengawas Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-secondary text-white shadow-sm h-100">
                <div class="card-body">
                    <h3>{{ $totalUser }}</h3>
                    <div class="small">Total User Sistem</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">

            <h5 class="mb-3 mb-md-0 fw-bold text-primary">Laporan Masuk</h5>

            <form action="{{ route('dashboard.admin') }}" method="GET" class="d-flex align-items-center gap-2">

                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        🗓️ Filter:
                    </span>
                    <input type="month" name="bulan_filter" class="form-control"
                        value="{{ request('bulan_filter') }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                @if(request('bulan_filter'))
                <a href="{{ route('dashboard.admin') }}" class="btn btn-secondary" title="Reset Filter">
                    X
                </a>
                @endif

            </form>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal & Waktu</th>
                            <th>Petugas (Pengawas)</th>
                            <th>Kegiatan</th>
                            <th>Lokasi</th>
                            <th class="text-center">File & Laporan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($seluruhLaporan as $laporan)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($laporan->tanggal_kegiatan)->locale('id')->translatedFormat('d F Y') }}</div>
                                <small class="text-muted">Input: {{ $laporan->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle text-center border me-2" style="width: 35px; height: 35px; line-height: 35px;">
                                        👤
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $laporan->user->name }}</div>
                                        <small class="text-muted">NIP: {{ $laporan->user->nip }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary mb-1">{{ $laporan->nomor_surat }}</span><br>
                                {{ Str::limit($laporan->judul_kegiatan, 30) }}
                            </td>
                            <td>
                                {{ $laporan->desa_kelurahan }},<br>
                                <small class="text-muted">Kec. {{ $laporan->kecamatan }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    {{-- 1. Scan Surat Tugas --}}
                                    @if($laporan->scan_surat_tugas_path)
                                    <a href="{{ asset('storage/' . $laporan->scan_surat_tugas_path) }}" download class="btn btn-sm btn-outline-dark" title="Lihat Scan Surat">
                                        📄 Scan
                                    </a>
                                    @endif

                                    {{-- 2. PDF Notulen --}}
                                    <a href="{{ route('pengawasan.cetak.notulen', $laporan->id) }}" target="_blank" class="btn btn-sm btn-warning" title="Cetak Notulen">
                                        📝 Notulen
                                    </a>

                                    {{-- 3. PDF Dokumentasi --}}
                                    <a href="{{ route('pengawasan.cetak.dokumentasi', $laporan->id) }}" target="_blank" class="btn btn-sm btn-info text-white" title="Cetak Dokumentasi">
                                        📷 Foto
                                    </a>

                                    {{-- Detail Web --}}
                                    <a href="{{ route('pengawasan.detail', $laporan->id) }}" class="btn btn-sm btn-primary" title="Detail Lengkap">
                                        🔍
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <p class="text-muted">Belum ada laporan yang masuk.</p>
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