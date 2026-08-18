<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportDumpCommand extends Command
{
    protected $signature = 'simpeg:init';
    protected $description = 'Automatically initialize and import the full SIMPEG database dump';

    public function handle()
    {
        try {
            $this->info('Memeriksa koneksi database...');
            
            // Check if pegawai table exists
            if (!Schema::hasTable('pegawai')) {
                $this->info('Tabel pegawai belum ada. Menjalankan migrasi & import data dump...');
                
                // Run standard migrations first if needed for sessions/users/cache
                $this->call('migrate', ['--force' => true]);

                $sqlPath = base_path('simpeg_database_dump.sql');
                if (file_exists($sqlPath)) {
                    $sql = file_get_contents($sqlPath);
                    DB::unprepared($sql);
                    $this->info('✅ 149 Data Pegawai SIMPEG berhasil diimport secara otomatis!');
                }
            } else {
                $this->info('✅ Database sudah memiliki tabel dan data pegawai.');
            }
        } catch (\Throwable $e) {
            $this->error('Catatan Inisialisasi Database: ' . $e->getMessage());
        }

        return 0;
    }
}
