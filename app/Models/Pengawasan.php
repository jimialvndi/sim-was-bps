<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengawasan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Tambahkan ini (karena skrg kita simpan ID pengawas langsung)
        'nomor_surat',
        'judul_kegiatan',
        'tanggal_surat',
        'tanggal_kegiatan',
        'kecamatan',
        'desa_kelurahan',
        'objek_pengawasan', // Ini bisa jadi "Tujuan" sesuai gambar notulen
        'hasil_temuan',     // Ini kita anggap deskripsi umum
        'permasalahan',
        'solusi_saran',
        'scan_surat_tugas_path'
    ];

    // Hapus function suratTugas() karena tabelnya sudah tidak dipakai relasinya
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokumentasis()
    {
        return $this->hasMany(Dokumentasi::class);
    }
}
