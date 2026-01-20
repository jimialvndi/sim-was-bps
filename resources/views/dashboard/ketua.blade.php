@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row mb-3">
    <div class="col-md-12">
      <h2>Dashboard Pimpinan</h2>
      <p class="text-muted">Ringkasan kegiatan pengawasan lapangan BPS.</p>
    </div>
  </div>

  <div class="row text-center mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="text-primary">{{ $totalKegiatan }}</h3>
          <span>Total Kegiatan</span>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="text-success">{{ $kegiatanBulanIni }}</h3>
          <span>Kegiatan Bulan Ini</span>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="text-info">{{ $totalPengawas }}</h3>
          <span>Pengawas Aktif</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Laporan Pengawasan Terbaru</span>
          <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Petugas</th>
                  <th>Kegiatan</th>
                  <th>Kecamatan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($laporanTerbaru as $laporan)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($laporan->tanggal_kegiatan)->format('d M Y') }}</td>
                  <td>{{ $laporan->suratTugas->user->name ?? 'N/A' }}</td>
                  <td>{{ $laporan->surat_tugas_id ? $laporan->suratTugas->judul_tugas : 'Insidentil' }}</td>
                  <td>{{ $laporan->kecamatan }}</td>
                <tr>
                  <td>
                    <a href="{{ route('pengawasan.detail', $laporan->id) }}" class="btn btn-sm btn-info text-white">
                      Detail
                    </a>
                  </td>
                </tr>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada data pengawasan masuk.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection