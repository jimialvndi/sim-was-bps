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
    Schema::create('dokumentasis', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke tabel Pengawasan
        $table->foreignId('pengawasan_id')->constrained('pengawasans')->onDelete('cascade');
        
        $table->string('foto_path'); // Path file gambar di storage
        $table->string('keterangan')->nullable(); // Caption foto (misal: 'Kondisi lahan')
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasis');
    }
};
