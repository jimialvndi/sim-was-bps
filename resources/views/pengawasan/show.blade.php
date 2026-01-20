@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    &larr; Kembali
                </a>

                <div class="btn-group" role="group">
                    {{-- 1. DOWNLOAD SCAN SURAT TUGAS --}}
                    @if($pengawasan->scan_surat_tugas_path)
                        <a href="{{ asset('storage/' . $pengawasan->scan_surat_tugas_path) }}" download class="btn btn-dark">
                            📄 Scan Surat Tugas
                        </a>
                    @endif

                    {{-- 2. DOWNLOAD PDF NOTULEN --}}
                    <a href="{{ route('pengawasan.cetak.notulen', $pengawasan->id) }}" target="_blank" class="btn btn-warning text-dark fw-bold">
                        📝 PDF Notulen
                    </a>

                    {{-- 3. DOWNLOAD PDF DOKUMENTASI --}}
                    <a href="{{ route('pengawasan.cetak.dokumentasi', $pengawasan->id) }}" target="_blank" class="btn btn-info text-white fw-bold">
                        📷 PDF Dokumentasi
                    </a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Laporan Pengawasan</h5>
                    <span class="badge bg-light text-primary">{{ $pengawasan->nomor_surat }}</span>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <h6 class="text-muted fw-bold mb-3">Informasi Surat Tugas</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="130" class="text-secondary">Nomor Surat</td>
                                    <td class="fw-bold">: {{ $pengawasan->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Kegiatan</td>
                                    <td class="fw-bold">: {{ $pengawasan->judul_kegiatan }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Petugas</td>
                                    <td>: {{ $pengawasan->user->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <h6 class="text-muted fw-bold mb-3">Pelaksanaan Lapangan</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="130" class="text-secondary">Tanggal</td>
                                    <td>: {{ \Carbon\Carbon::parse($pengawasan->tanggal_kegiatan)->isoFormat('D MMMM Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Lokasi</td>
                                    <td>: {{ $pengawasan->desa_kelurahan }}, Kec. {{ $pengawasan->kecamatan }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Tujuan</td>
                                    <td>: {{ $pengawasan->objek_pengawasan }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

<div class="mb-4">
                        <h5 class="text-primary fw-bold mb-3"><i class="bi bi-journal-text"></i> Isi Notulen</h5>
                        
                        @php
                            $masalahList = json_decode($pengawasan->permasalahan);
                            $solusiList = json_decode($pengawasan->solusi_saran);
                            
                            $masalahList = is_array($masalahList) ? $masalahList : [$pengawasan->permasalahan];
                            $solusiList = is_array($solusiList) ? $solusiList : [$pengawasan->solusi_saran];
                            
                            $count = max(count($masalahList), count($solusiList));
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50" class="text-center">No</th>
                                        <th>Permasalahan</th>
                                        <th>Solusi / Saran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i < $count; $i++)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>{{ $masalahList[$i] ?? '-' }}</td>
                                        <td>{{ $solusiList[$i] ?? '-' }}</td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h5 class="text-primary fw-bold mb-3"><i class="bi bi-images"></i> Dokumentasi Lapangan</h5>
                    
                    @if($pengawasan->dokumentasis->count() > 0)
                        <div class="row g-3">
                            @foreach($pengawasan->dokumentasis as $foto)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card h-100 shadow-sm">
                                        <a href="{{ asset('storage/' . $foto->foto_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $foto->foto_path) }}" class="card-img-top" alt="Dokumentasi" style="height: 220px; object-fit: cover;">
                                        </a>
                                        <div class="card-footer bg-white text-center text-muted small py-2">
                                            Foto Dokumentasi
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            Tidak ada foto dokumentasi yang diupload.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection