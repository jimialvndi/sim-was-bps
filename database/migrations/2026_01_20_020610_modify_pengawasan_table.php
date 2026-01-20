<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pengawasans', function (Blueprint $table) {
            // 1. Hapus foreign key lama
            // (Pastikan nama foreign key ini benar, jika error saat migrate:fresh nanti kita cek lagi)
            $table->dropForeign(['surat_tugas_id']); 
            $table->dropColumn('surat_tugas_id');

            // --- TAMBAHKAN BARIS INI (PENTING!) ---
            // Kita taruh user_id setelah kolom id agar rapi
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            // --------------------------------------

            // 2. Kolom pengganti lainnya
            $table->string('nomor_surat')->after('user_id'); // sesuaikan after-nya
            $table->string('judul_kegiatan')->after('nomor_surat');
            $table->date('tanggal_surat')->after('judul_kegiatan')->nullable();
            
            // 3. Kolom Scan
            $table->string('scan_surat_tugas_path')->after('solusi_saran')->nullable();
        });
    }

    public function down()
    {
        // Kembalikan jika rollback (Opsional untuk development)
    }
};
