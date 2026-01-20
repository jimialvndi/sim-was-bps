<!DOCTYPE html>
<html>

<head>
    <title>Notulen</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            vertical-align: top;
        }

        .header-info tr td {
            border: none;
            padding: 5px 0;
        }

        .label {
            width: 150px;
        }

        .ttd-area { 
            margin-top: 50px; 
            float: right; 
            width: 250px; 
            text-align: center; 
            
            /* --- TAMBAHKAN 2 BARIS INI (KUNCI PERBAIKAN) --- */
            page-break-inside: avoid; /* Mencegah potong halaman di dalam elemen ini */
            -webkit-column-break-inside: avoid; /* Cadangan untuk kompatibilitas */
        }
    </style>
</head>

<body>

    <h3 class="text-center bold mb-20">NOTULEN</h3>

    <table class="header-info" style="border: none;">
        <tr>
            <td class="label">Kecamatan</td>
            <td>: {{ $pengawasan->kecamatan }}</td>
        </tr>
        <tr>
            <td class="label">Tujuan</td>
            <td>: {{ $pengawasan->objek_pengawasan }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal tugas</td>
            <td>: {{ \Carbon\Carbon::parse($pengawasan->tanggal_kegiatan)->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 47%;">Permasalahan</th>
                <th style="width: 48%;">Solusi</th>
            </tr>
        </thead>
        <tbody>
            @php
            // Decode JSON menjadi Array PHP
            // Jika data lama msh teks biasa, kita bungkus jadi array agar tidak error
            $masalahRaw = json_decode($pengawasan->permasalahan);
            $solusiRaw = json_decode($pengawasan->solusi_saran);

            // Cek apakah hasil decode valid array? Jika tidak (berarti data lama/teks biasa), jadikan array manual
            $daftarMasalah = is_array($masalahRaw) ? $masalahRaw : [$pengawasan->permasalahan];
            $daftarSolusi = is_array($solusiRaw) ? $solusiRaw : [$pengawasan->solusi_saran];

            // Hitung jumlah terbanyak (untuk looping)
            $maxRows = max(count($daftarMasalah), count($daftarSolusi));
            @endphp

            @for($i = 0; $i < $maxRows; $i++)
                <tr>
                <td class="text-center">{{ $i + 1 }}.</td>
                <td>
                    {{ $daftarMasalah[$i] ?? '-' }}
                </td>
                <td>
                    {{ $daftarSolusi[$i] ?? '-' }}
                </td>
                </tr>
                @endfor

                @if($maxRows < 2)
                    <tr>
                    <td class="text-center">{{ $maxRows + 1 }}.</td>
                    <td style="height: 100px;"></td>
                    <td></td>
                    </tr>
                    @endif
        </tbody>
    </table>

    <div class="ttd-area">
        <p>Bengkayang, {{ \Carbon\Carbon::parse($pengawasan->tanggal_kegiatan)->locale('id')->translatedFormat('d F Y') }}</p>
        <p>Petugas Lapangan</p>
        <br><br><br>
        <p style="text-decoration: underline; font-weight: bold;">( {{ strtoupper($pengawasan->user->name) }} )</p>
        <p>NIP. {{ $pengawasan->user->nip ?? '......................' }}</p>
    </div>

</body>

</html>