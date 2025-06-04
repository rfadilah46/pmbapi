<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formulir', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            
            // Data Pribadi
            $table->string('nama');
            $table->string('nik', 16)->unique();
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->string('email');
            
            // Data Orang Tua
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ayah');
            $table->string('pekerjaan_ibu');
            $table->string('nik_ayah', 16);
            $table->string('nik_ibu', 16);
            $table->string('no_hp_ortu', 15);
            
            // Data Pendidikan
            $table->string('asal_sekolah');
            $table->year('tahun_lulus');
            $table->decimal('nilai_ijazah', 5, 2);
            
            // Program Studi yang dipilih
            $table->foreignId('program_studi_id')->constrained('program_studi');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran');
            
            // File Uploads
            $table->string('pas_foto')->nullable();
            $table->string('scan_ktp')->nullable();
            $table->string('scan_ijazah')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            
            // Status
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulir');
    }
};
