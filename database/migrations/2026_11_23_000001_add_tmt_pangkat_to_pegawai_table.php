<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawai', 'tmt_pangkat')) {
                $table->date('tmt_pangkat')->nullable()->after('tmt_jabatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            if (Schema::hasColumn('pegawai', 'tmt_pangkat')) {
                $table->dropColumn('tmt_pangkat');
            }
        });
    }
};
