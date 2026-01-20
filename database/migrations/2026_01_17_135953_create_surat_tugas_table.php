<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id();

            // Relasi ke User (Siapa yang ditugaskan)
            // onDelete('cascade') berarti jika User dihapus, surat tugasnya ikut terhapus
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('nomor_surat'); // No Surat: B-123/BPS/9999
            $table->string('judul_tugas'); // Misal: Pengawasan Survei Ubinan Subround 1
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('file_path'); // Lokasi file PDF surat tugas di storage

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};
