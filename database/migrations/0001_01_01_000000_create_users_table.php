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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Lengkap
            $table->string('nip', 18)->unique()->nullable(); // NIP BPS biasanya 18 digit
            $table->string('jabatan')->nullable(); // Misal: Staff IPDS, Staff Produksi
            $table->string('email')->unique();
            $table->string('password');

            // Role management simpel menggunakan ENUM
            // admin = Admin BPS Kab
            // ketua = Kepala BPS / Penanggung Jawab
            // pengawas = Petugas Lapangan (Organik/Mitra)
            $table->enum('role', ['admin', 'ketua', 'pengawas'])->default('pengawas');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
