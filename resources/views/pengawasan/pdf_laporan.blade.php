<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pengawasan Lapangan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2 { margin: 0; }
        .header p { margin: 2px; }
        .content { margin-top: 20px; }
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-data td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 150px; }
        
        .foto-container { margin-top: 20px; text-align: center; }
        .foto-item { display: inline-block; margin: 10px; width: 45%; border: 1px solid #ddd; padding: 5px; }
        .foto-item img { max-width: 100%; height: auto; }
        
        .ttd-area { margin-top: 50px; width: 100%; }
        .ttd-box { width: 40%; float: right; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>BADAN PUSAT STATISTIK</h2>
        <p>KABUPATEN CONTOH</p>
        <p>Jl. Merdeka No. 123, Kota Contoh</p>
    </div>

    <div class="content">
        <h3 style="text-align: center; text-decoration: underline;">LAPORAN HASIL PENGAWASAN LAPANGAN</h3>
        
        <table class="table-data">
            <tr>
                <td class="label">Dasar Surat Tugas</td>
                <td>: {{ $pengawasan->suratTugas->nomor_surat }}</td>
            </tr>
            <tr>
                <td class="label">Nama Petugas</td>
                <td>: {{ $pengawasan->suratTugas->user->name }} (NIP: {{ $pengawasan->suratTugas->user->nip }})</td>
            </tr>
            <tr>
                <td class="label">Kegiatan</td>
                <td>: {{ $pengawasan->suratTugas->judul_tugas }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pengawasan</td>
                <td>: {{ \Carbon\Carbon::parse($pengawasan->tanggal_kegiatan)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Lokasi</td>
                <td>: Desa {{ $pengawasan->desa_kelurahan }}, Kec. {{ $pengawasan->kecamatan }}</td>
            </tr>
            <tr>
                <td class="label">Objek Pengawasan</td>
                <td>: {{ $pengawasan->objek_pengawasan }}</td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr>
                <td class="label">Hasil Temuan</td>
                <td>: <br>{!! nl2br(e($pengawasan->hasil_temuan)) !!}</td>
            </tr>
            <tr>
                <td class="label">Permasalahan</td>
                <td>: <br>{!! nl2br(e($pengawasan->permasalahan ?? '-')) !!}</td>
            </tr>
            <tr>
                <td class="label">Solusi / Saran</td>
                <td>: <br>{!! nl2br(e($pengawasan->solusi_saran ?? '-')) !!}</td>
            </tr>
        </table>

        @if($pengawasan->dokumentasis->count() > 0)
        <div style="page-break-inside: avoid;">
            <h4>Lampiran Dokumentasi:</h4>
            <div class="foto-container">
                @foreach($pengawasan->dokumentasis as $foto)
                    <div class="foto-item">
                        <img src="{{ public_path('storage/' . $foto->foto_path) }}">
                        <br>
                        <small>{{ $foto->keterangan }}</small>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="ttd-area">
            <div class="ttd-box">
                <p>Diketahui Oleh,<br>Kepala BPS Kabupaten</p>
                <br><br><br>
                <p><strong>(Nama Kepala)</strong><br>NIP. ....................</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

</body>
</html>