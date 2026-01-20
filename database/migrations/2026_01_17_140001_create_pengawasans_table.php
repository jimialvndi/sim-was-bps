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
    Schema::create('pengawasans', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke Surat Tugas (Opsional: boleh null jika pengawasan insidentil)
        // Tapi sebaiknya dikaitkan agar rapi.
        $table->foreignId('surat_tugas_id')->constrained('surat_tugas')->onDelete('cascade');
        
        // Detail Lokasi & Waktu
        $table->date('tanggal_kegiatan');
        $table->string('kecamatan'); 
        $table->string('desa_kelurahan');
        
        // Isi Notulen / Laporan
        $table->string('objek_pengawasan'); // Misal: Responden Rumah Tangga Tani
        $table->text('hasil_temuan'); // Apa yang ditemukan di lapangan
        $table->text('permasalahan')->nullable(); // Jika ada kendala
        $table->text('solusi_saran')->nullable(); // Tindak lanjut
        
        // Koordinat (Opsional, fitur nice-to-have untuk GIS masa depan)
        $table->string('latitude')->nullable();
        $table->string('longitude')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengawasans');
    }
};
