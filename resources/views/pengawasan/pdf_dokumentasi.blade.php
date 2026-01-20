<!DOCTYPE html>
<html>
<head>
    <title>Dokumentasi</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .header { margin-bottom: 30px; font-weight: bold; text-transform: uppercase; font-size: 11pt; line-height: 1.5; }
        
        .foto-box { 
            border: 0px solid black; 
            width: 80%; 
            margin: 0 auto 20px auto; 
            padding: 10px; 
            min-height: 300px;
            page-break-inside: avoid;
        }
        
        .foto-label { margin-bottom: 10px; font-weight: bold; }
        img { max-width: 95%; max-height: 280px; }
    </style>
</head>
<body>

    <div class="header">
        PENGAWASAN PENDATAAN LAPANGAN {{ strtoupper($pengawasan->judul_kegiatan) }}<br>
        KECAMATAN {{ strtoupper($pengawasan->kecamatan) }}<br>
        Tanggal {{ \Carbon\Carbon::parse($pengawasan->tanggal_kegiatan)->locale('id')->translatedFormat('d F Y') }}
    </div>

    @forelse($pengawasan->dokumentasis as $index => $foto)
        <div class="foto-box">
            <div class="foto-label">Dokumentasi {{ $index + 1 }}</div>
            <img src="{{ public_path('storage/' . $foto->foto_path) }}">
        </div>
    @empty
        <div class="foto-box">
            <p style="padding-top: 100px;">Tidak ada foto dokumentasi</p>
        </div>
    @endforelse

</body>
</html>