<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasis'; // Memastikan nama tabel sesuai

    protected $fillable = [
        'pengawasan_id',
        'foto_path',
        'keterangan'
    ];

    // RELASI KE ATAS: Foto ini milik pengawasan yang mana?
    public function pengawasan()
    {
        return $this->belongsTo(Pengawasan::class, 'pengawasan_id');
    }
}