<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_pegawai_id')->constrained('kategori_pegawai');
            $table->foreignId('status_kepegawaian_id')->constrained('status_kepegawaian');
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja');
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete();
            $table->foreignId('formasi_jabatan_id')->nullable()->constrained('formasi_jabatan')->nullOnDelete();
            $table->string('nama', 200);
            $table->string('nip', 30)->nullable()->index();
            $table->string('nik', 30)->nullable()->index();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan', 100)->nullable();
            $table->string('golongan', 30)->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
