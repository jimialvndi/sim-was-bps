@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Form Laporan Pengawasan Lapangan</div>
                <div class="card-body">
                    <form action="{{ route('pengawasan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h6 class="text-primary mb-3 border-bottom pb-2">A. Identitas & Surat Tugas</h6>
                        
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Nomor Surat Tugas</label>
                            <div class="col-md-9">
                                <input type="text" name="nomor_surat" class="form-control" placeholder="Contoh: B-001/BPS/..." required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Scan Surat Tugas</label>
                            <div class="col-md-9">
                                <input type="file" name="scan_surat_tugas" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Judul Kegiatan</label>
                            <div class="col-md-9">
                                <input type="text" name="judul_kegiatan" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Tanggal Kegiatan</label>
                            <div class="col-md-4">
                                <input type="date" name="tanggal_kegiatan" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Lokasi</label>
                            <div class="col-md-4 mb-2">
                                <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan" required>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="desa_kelurahan" class="form-control" placeholder="Desa / Kelurahan" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tujuan (Objek Pengawasan)</label>
                            <input type="text" name="objek_pengawasan" class="form-control" placeholder="Tujuan pengawasan pendataan..." required>
                        </div>

                        <h6 class="text-primary mt-4 mb-3 border-bottom pb-2">B. Notulen (Masalah & Solusi)</h6>
                        <div class="alert alert-info py-2 small">
                            <i class="bi bi-info-circle"></i> Klik tombol <b>"Tambah Poin"</b> jika ada lebih dari satu masalah.
                        </div>

                        <div id="notulen-container">
                            <div class="row mb-2 input-row">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Permasalahan 1</label>
                                    <textarea name="permasalahan[]" rows="2" class="form-control" placeholder="Masalah..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Solusi 1</label>
                                    <textarea name="solusi[]" rows="2" class="form-control" placeholder="Solusi..."></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-success mt-2" onclick="addNotulenRow()">
                            + Tambah Poin Masalah & Solusi
                        </button>

                        <h6 class="text-primary mt-4 mb-3 border-bottom pb-2">C. Dokumentasi</h6>
                        
                        <div id="foto-container">
                            <div class="mb-2">
                                <label class="form-label">Foto 1</label>
                                <input type="file" name="foto[]" class="form-control" accept="image/*" required>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addFotoRow()">
                            + Tambah File Foto Lain
                        </button>

                        <hr class="mt-4">
                        <button type="submit" class="btn btn-primary px-4 w-100 py-2">Simpan Laporan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let notulenCount = 1;
    let fotoCount = 1;

    function addNotulenRow() {
        notulenCount++;
        const container = document.getElementById('notulen-container');
        const html = `
            <div class="row mb-2 mt-3 input-row border-top pt-2">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Permasalahan ${notulenCount}</label>
                    <textarea name="permasalahan[]" rows="2" class="form-control" placeholder="Masalah..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Solusi ${notulenCount}</label>
                    <textarea name="solusi[]" rows="2" class="form-control" placeholder="Solusi..."></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addFotoRow() {
        fotoCount++;
        const container = document.getElementById('foto-container');
        const html = `
            <div class="mb-2 mt-2">
                <label class="form-label">Foto ${fotoCount}</label>
                <input type="file" name="foto[]" class="form-control" accept="image/*">
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
@endsection