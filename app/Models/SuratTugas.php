<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;

    // Definisikan nama tabel karena Laravel mungkin bingung dengan pluralnya
    protected $table = 'surat_tugas';

    protected $fillable = [
        'user_id',
        'nomor_surat',
        'judul_tugas',
        'tgl_mulai',
        'tgl_selesai',
        'file_path'
    ];

    // RELASI KE ATAS: Dimiliki oleh User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // RELASI KE BAWAH: Satu surat tugas bisa memiliki banyak laporan pengawasan
    public function pengawasans()
    {
        return $this->hasMany(Pengawasan::class, 'surat_tugas_id');
    }
}