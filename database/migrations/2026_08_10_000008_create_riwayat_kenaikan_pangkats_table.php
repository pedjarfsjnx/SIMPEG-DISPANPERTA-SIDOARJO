<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kenaikan_pangkat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('golongan_lama', 30)->nullable();
            $table->string('golongan_baru', 30)->nullable();
            $table->date('tmt_diusulkan')->nullable();
            $table->string('status_pengajuan', 50)->default('proses');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kenaikan_pangkat');
    }
};
