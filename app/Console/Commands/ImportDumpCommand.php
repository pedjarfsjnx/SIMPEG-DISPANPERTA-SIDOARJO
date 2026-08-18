<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDumpCommand extends Command
{
    protected $signature = 'simpeg:init';
    protected $description = 'Automatically initialize and import the full SIMPEG database dump';

    public function handle()
    {
        try {
            $this->info('Memeriksa koneksi dan tabel database...');
            $tables = DB::select('SHOW TABLES');
            
            if (empty($tables)) {
                $this->info('Database kosong. Memulai import otomatis simpeg_database_dump.sql (149 data pegawai)...');
                $sqlPath = base_path('simpeg_database_dump.sql');
                if (file_exists($sqlPath)) {
                    $sql = file_get_contents($sqlPath);
                    DB::unprepared($sql);
                    $this->info('Database SIMPEG berhasil diimport secara otomatis!');
                } else {
                    $this->warn('File simpeg_database_dump.sql tidak ditemukan.');
                }
            } else {
                $this->info('Tabel database sudah ada. Melewati import otomatis.');
            }
        } catch (\Throwable $e) {
            $this->error('Gagal inisialisasi database: ' . $e->getMessage());
        }

        return 0;
    }
}