<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pegawai;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\Bidang;
use App\Models\FormasiJabatan;
use App\Models\RiwayatPensiun;
use App\Models\RiwayatKenaikanPangkat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    public function readCell($columnAddress, $row, $worksheetName = ''): bool
    {
        if ($row <= 500) {
            $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnAddress);
            if ($colIndex <= 12) {
                return true;
            }
        }
        return false;
    }
}

class ImportRealExcelData extends Command
{
    protected $signature = 'import:real-data';
    protected $description = 'Impor seluruh data nyata pegawai Dinas Pangan dan Pertanian 2026 dari file Excel acuan';

    public function handle()
    {
        ini_set('memory_limit', '2048M');
        $this->info('Memulai impor data nyata pegawai TAHUN 2026 dari Excel...');

        $baseDir = base_path('DATA KEPEGAWAIAN DINAS PANGAN & PERTANIAN SIDOARJO');
        $filePns = $baseDir . '/DATA PEGAWAI DINAS.xlsx';
        $filePangkat = $baseDir . '/DAFTAR PANGKAT DAN JABATAN 2026.xlsx';

        if (!file_exists($filePns)) {
            $this->error("File tidak ditemukan: {$filePns}");
            return 1;
        }

        // Clean existing tables before fresh 2026 real import
        RiwayatPensiun::query()->delete();
        RiwayatKenaikanPangkat::query()->delete();
        Pegawai::query()->forceDelete();
        FormasiJabatan::query()->delete();
        Bidang::query()->delete();

        // Master setup
        $pnsKat = KategoriPegawai::firstOrCreate(['nama' => 'PNS']);
        $pppkKat = KategoriPegawai::firstOrCreate(['nama' => 'PPPK']);
        $pppkParuhKat = KategoriPegawai::firstOrCreate(['nama' => 'PPPK Paruh Waktu']);
        $swakelolaKat = KategoriPegawai::firstOrCreate(['nama' => 'Swakelola']);
        $outsourcingKat = KategoriPegawai::firstOrCreate(['nama' => 'Outsourcing']);

        $aktifStatus = StatusKepegawaian::firstOrCreate(['nama' => 'Aktif']);

        $dinasUnit = UnitKerja::firstOrCreate(
            ['nama' => 'Dinas Pangan dan Pertanian (Induk)'],
            ['tipe' => 'Dinas Induk', 'aktif' => true]
        );

        $uptdRphUnit = UnitKerja::firstOrCreate(
            ['nama' => 'UPTD Rumah Potong Hewan dan Pasar Hewan'],
            ['tipe' => 'UPTD', 'aktif' => true]
        );

        $uptdLabUnit = UnitKerja::firstOrCreate(
            ['nama' => 'UPTD Laboratorium Kesehatan Hewan dan Kesmavet'],
            ['tipe' => 'UPTD', 'aktif' => true]
        );

        // Official Bidangs under Dinas Induk
        $bidangSekretariat = Bidang::create(['unit_kerja_id' => $dinasUnit->id, 'nama' => 'Sekretariat']);
        $bidangPeternakan = Bidang::create(['unit_kerja_id' => $dinasUnit->id, 'nama' => 'Bidang Produksi Peternakan, Kesehatan Hewan dan Kesmavet']);
        $bidangTanamanPangan = Bidang::create(['unit_kerja_id' => $dinasUnit->id, 'nama' => 'Bidang Tanaman Pangan dan Hortikultura']);
        $bidangSarpras = Bidang::create(['unit_kerja_id' => $dinasUnit->id, 'nama' => 'Bidang Sarana Prasarana dan Penyuluhan Pertanian']);
        $bidangKetahananPangan = Bidang::create(['unit_kerja_id' => $dinasUnit->id, 'nama' => 'Bidang Ketahanan Pangan']);

        $resolveBidang = function($text) use ($bidangSekretariat, $bidangPeternakan, $bidangTanamanPangan, $bidangSarpras, $bidangKetahananPangan) {
            $upper = strtoupper((string)$text);
            if (str_contains($upper, 'SEKRETARIS') || str_contains($upper, 'SEKRETARIAT') || str_contains($upper, 'SUB BAGIAN') || str_contains($upper, 'TATA USAHA')) {
                return $bidangSekretariat;
            }
            if (str_contains($upper, 'PETERNAKAN') || str_contains($upper, 'HEWAN') || str_contains($upper, 'KESMAVET')) {
                return $bidangPeternakan;
            }
            if (str_contains($upper, 'TANAMAN PANGAN') || str_contains($upper, 'HORTIKULTURA')) {
                return $bidangTanamanPangan;
            }
            if (str_contains($upper, 'SARANA') || str_contains($upper, 'PRASARANA') || str_contains($upper, 'PENYULUHAN')) {
                return $bidangSarpras;
            }
            if (str_contains($upper, 'KETAHANAN PANGAN') || str_contains($upper, 'PANGAN')) {
                return $bidangKetahananPangan;
            }
            return $bidangSekretariat;
        };

        // Load File 1
        $reader = IOFactory::createReaderForFile($filePns);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new ChunkReadFilter());

        $spreadsheet = $reader->load($filePns);

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $sheetName = trim($sheet->getTitle());

            // EXPLICIT FILTER: Skip Sheet 2025 ('DATA SESUI EXISTING JABATAN')
            if (str_contains(strtoupper($sheetName), 'EXISTING') || str_contains(strtoupper($sheetName), '2025')) {
                $this->info("Mengabaikan sheet versi lama (2025): {$sheetName}");
                continue;
            }

            // 1. Sheet TAHUN 2026: DATA RIEL NAMA DAN JABATAN (PNS)
            if (trim(strtoupper($sheetName)) === 'DATA RIEL NAMA DAN JABATAN') {
                $rows = $sheet->toArray();
                $headerFound = false;
                $countPns = 0;
                $countKosong = 0;

                $currentUnit = $dinasUnit;
                $currentBidang = $bidangSekretariat;

                foreach ($rows as $row) {
                    if (!$row || count(array_filter($row)) === 0) continue;

                    $col0 = strtoupper((string)($row[0] ?? ''));
                    $col1 = strtoupper((string)($row[1] ?? ''));

                    if (!$headerFound && (str_contains($col0, 'NO') || str_contains($col1, 'NAMA'))) {
                        $headerFound = true;
                        continue;
                    }

                    if ($headerFound) {
                        $nama = trim((string)($row[1] ?? ''));
                        $nip = trim((string)($row[2] ?? ''));
                        $jabatan = trim((string)($row[3] ?? ''));
                        $gol = trim((string)($row[4] ?? ''));
                        $kelas = trim((string)($row[5] ?? ''));
                        $pend = trim((string)($row[6] ?? ''));

                        if (str_starts_with($nip, "'")) {
                            $nip = substr($nip, 1);
                        }

                        if (str_contains(strtoupper($nama), 'KEPALA DINAS PANGAN') && str_contains(strtoupper($nama), 'PEMERINTAH')) continue;
                        if (str_contains(strtoupper($nama), 'PEMERINTAH KABUPATEN')) continue;

                        $jabatanUpper = strtoupper($jabatan);

                        if (str_contains($jabatanUpper, 'UPTD RPH') || str_contains($jabatanUpper, 'PASAR HEWAN')) {
                            $currentUnit = $uptdRphUnit;
                            $currentBidang = null;
                        } elseif (str_contains($jabatanUpper, 'UPTD LAB') || str_contains($jabatanUpper, 'KESVET')) {
                            $currentUnit = $uptdLabUnit;
                            $currentBidang = null;
                        } else {
                            if (str_contains($jabatanUpper, 'SEKRETARIAT') || str_contains($jabatanUpper, 'SEKRETARIS')) {
                                $currentUnit = $dinasUnit;
                                $currentBidang = $bidangSekretariat;
                            } elseif (str_contains($jabatanUpper, 'PETERNAKAN') || str_contains($jabatanUpper, 'KESMAVET')) {
                                $currentUnit = $dinasUnit;
                                $currentBidang = $bidangPeternakan;
                            } elseif (str_contains($jabatanUpper, 'TANAMAN PANGAN') || str_contains($jabatanUpper, 'HORTIKULTURA')) {
                                $currentUnit = $dinasUnit;
                                $currentBidang = $bidangTanamanPangan;
                            } elseif (str_contains($jabatanUpper, 'SARANA') || str_contains($jabatanUpper, 'PRASARANA') || str_contains($jabatanUpper, 'PENYULUHAN')) {
                                $currentUnit = $dinasUnit;
                                $currentBidang = $bidangSarpras;
                            } elseif (str_contains($jabatanUpper, 'KETAHANAN PANGAN')) {
                                $currentUnit = $dinasUnit;
                                $currentBidang = $bidangKetahananPangan;
                            }
                        }

                        if (!empty($jabatan)) {
                            $formasi = FormasiJabatan::create([
                                'unit_kerja_id' => $currentUnit->id,
                                'bidang_id' => $currentBidang?->id,
                                'nama_jabatan' => $jabatan,
                                'kelas_jabatan' => $kelas ?: null,
                                'status_formasi' => !empty($nama) ? 'terisi' : 'kosong',
                            ]);

                            if (!empty($nama)) {
                                Pegawai::create([
                                    'kategori_pegawai_id' => $pnsKat->id,
                                    'status_kepegawaian_id' => $aktifStatus->id,
                                    'unit_kerja_id' => $currentUnit->id,
                                    'bidang_id' => $currentBidang?->id,
                                    'formasi_jabatan_id' => $formasi->id,
                                    'nama' => $nama,
                                    'nip' => $nip ?: null,
                                    'golongan' => $gol ?: null,
                                    'pendidikan' => $pend ?: null,
                                ]);
                                $countPns++;
                            } else {
                                $countKosong++;
                            }
                        }
                    }
                }
                $this->info("✓ Berhasil mengimpor {$countPns} pegawai PNS TAHUN 2026 dan {$countKosong} formasi jabatan kosong.");
            }

            // 2. Sheet TAHUN 2026: DATA PPPK PARUH WAKTU
            if (trim(strtoupper($sheetName)) === 'DATA PPPK PARUH WAKTU') {
                $rows = $sheet->toArray();
                $headerFound = false;
                $countParuh = 0;

                foreach ($rows as $row) {
                    if (!$row || count(array_filter($row)) === 0) continue;

                    $col1 = strtoupper((string)($row[1] ?? ''));
                    if (!$headerFound && str_contains($col1, 'NAMA')) {
                        $headerFound = true;
                        continue;
                    }

                    if ($headerFound) {
                        $nama = trim((string)($row[1] ?? ''));
                        $nip = trim((string)($row[2] ?? ''));
                        $tmpLahir = trim((string)($row[3] ?? ''));
                        $tglLahirRaw = trim((string)($row[4] ?? ''));
                        $pend = trim((string)($row[5] ?? ''));
                        $jabatan = trim((string)($row[6] ?? ''));
                        $unitStr = trim((string)($row[7] ?? ''));
                        $hp = trim((string)($row[8] ?? ''));
                        $email = trim((string)($row[9] ?? ''));

                        if (empty($nama) || str_contains(strtoupper($nama), 'PEMERINTAH') || str_contains(strtoupper($nama), 'NAMA')) continue;

                        if (str_starts_with($nip, "'")) {
                            $nip = substr($nip, 1);
                        }

                        $unitUpper = strtoupper($unitStr);
                        if (str_contains($unitUpper, 'RPH') || str_contains($unitUpper, 'PASAR HEWAN')) {
                            $targetUnit = $uptdRphUnit;
                            $targetBidang = null;
                        } elseif (str_contains($unitUpper, 'LAB') || str_contains($unitUpper, 'KESMAVET')) {
                            $targetUnit = $uptdLabUnit;
                            $targetBidang = null;
                        } else {
                            $targetUnit = $dinasUnit;
                            $targetBidang = $resolveBidang($unitStr);
                        }

                        $formasi = null;
                        if (!empty($jabatan)) {
                            $formasi = FormasiJabatan::create([
                                'unit_kerja_id' => $targetUnit->id,
                                'bidang_id' => $targetBidang?->id,
                                'nama_jabatan' => $jabatan,
                                'status_formasi' => 'terisi',
                            ]);
                        }

                        Pegawai::create([
                            'kategori_pegawai_id' => $pppkParuhKat->id,
                            'status_kepegawaian_id' => $aktifStatus->id,
                            'unit_kerja_id' => $targetUnit->id,
                            'bidang_id' => $targetBidang?->id,
                            'formasi_jabatan_id' => $formasi?->id,
                            'nama' => $nama,
                            'nip' => $nip ?: null,
                            'pendidikan' => $pend ?: null,
                            'no_hp' => $hp ?: null,
                            'email' => $email ?: null,
                        ]);
                        $countParuh++;
                    }
                }
                $this->info("✓ Berhasil mengimpor {$countParuh} pegawai PPPK Paruh Waktu TAHUN 2026.");
            }

            // 3. Sheet TAHUN 2026: PEGAWAI SWAKELOLA
            if (trim(strtoupper($sheetName)) === 'PEGAWAI SWAKELOLA') {
                $rows = $sheet->toArray();
                $headerFound = false;
                $countSwa = 0;

                foreach ($rows as $row) {
                    if (!$row || count(array_filter($row)) === 0) continue;
                    $col1 = strtoupper((string)($row[1] ?? ''));

                    if (!$headerFound && str_contains($col1, 'NAMA')) {
                        $headerFound = true;
                        continue;
                    }

                    if ($headerFound) {
                        $nama = trim((string)($row[1] ?? ''));
                        $nik = trim((string)($row[2] ?? ''));
                        $jabatan = trim((string)($row[3] ?? ''));
                        $pend = trim((string)($row[4] ?? ''));
                        $unitStr = trim((string)($row[5] ?? ''));

                        if (empty($nama) || str_contains(strtoupper($nama), 'NAMA')) continue;

                        $unitUpper = strtoupper($unitStr);
                        $targetUnit = str_contains($unitUpper, 'RPH') ? $uptdRphUnit : $dinasUnit;

                        $formasi = null;
                        if (!empty($jabatan)) {
                            $formasi = FormasiJabatan::create([
                                'unit_kerja_id' => $targetUnit->id,
                                'nama_jabatan' => $jabatan,
                                'status_formasi' => 'terisi',
                            ]);
                        }

                        Pegawai::create([
                            'kategori_pegawai_id' => $swakelolaKat->id,
                            'status_kepegawaian_id' => $aktifStatus->id,
                            'unit_kerja_id' => $targetUnit->id,
                            'formasi_jabatan_id' => $formasi?->id,
                            'nama' => $nama,
                            'nik' => $nik ?: null,
                            'pendidikan' => $pend ?: null,
                        ]);
                        $countSwa++;
                    }
                }
                $this->info("✓ Berhasil mengimpor {$countSwa} pegawai Swakelola TAHUN 2026.");
            }

            // 4. Sheet TAHUN 2026: PEGAWAI OUTSURCHING
            if (trim(strtoupper($sheetName)) === 'PEGAWAI OUTSURCHING' || trim(strtoupper($sheetName)) === 'PEGAWAI OUTSOURCING') {
                $rows = $sheet->toArray();
                $headerFound = false;
                $countOut = 0;

                foreach ($rows as $row) {
                    if (!$row || count(array_filter($row)) === 0) continue;
                    $col1 = strtoupper((string)($row[1] ?? ''));

                    if (!$headerFound && str_contains($col1, 'NAMA')) {
                        $headerFound = true;
                        continue;
                    }

                    if ($headerFound) {
                        $nama = trim((string)($row[1] ?? ''));
                        $nik = trim((string)($row[2] ?? ''));
                        $jabatan = trim((string)($row[3] ?? ''));
                        $pend = trim((string)($row[4] ?? ''));
                        $unitStr = trim((string)($row[5] ?? ''));

                        if (empty($nama) || str_contains(strtoupper($nama), 'NAMA')) continue;

                        $unitUpper = strtoupper($unitStr);
                        $targetUnit = str_contains($unitUpper, 'RPH') ? $uptdRphUnit : $dinasUnit;

                        $formasi = null;
                        if (!empty($jabatan)) {
                            $formasi = FormasiJabatan::create([
                                'unit_kerja_id' => $targetUnit->id,
                                'nama_jabatan' => $jabatan,
                                'status_formasi' => 'terisi',
                            ]);
                        }

                        Pegawai::create([
                            'kategori_pegawai_id' => $outsourcingKat->id,
                            'status_kepegawaian_id' => $aktifStatus->id,
                            'unit_kerja_id' => $targetUnit->id,
                            'formasi_jabatan_id' => $formasi?->id,
                            'nama' => $nama,
                            'nik' => $nik ?: null,
                            'pendidikan' => $pend ?: null,
                        ]);
                        $countOut++;
                    }
                }
                $this->info("✓ Berhasil mengimpor {$countOut} pegawai Outsourcing TAHUN 2026.");
            }
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        gc_collect_cycles();

        // Load File 2: DAFTAR PANGKAT DAN JABATAN 2026.xlsx
        if (file_exists($filePangkat)) {
            $this->info('Membaca DAFTAR PANGKAT DAN JABATAN 2026.xlsx...');
            $reader2 = IOFactory::createReaderForFile($filePangkat);
            $reader2->setReadDataOnly(true);
            $reader2->setReadFilter(new ChunkReadFilter());

            $spreadsheet2 = $reader2->load($filePangkat);

            foreach ($spreadsheet2->getWorksheetIterator() as $sheet2) {
                $sheetName2 = trim($sheet2->getTitle());

                if (str_contains(strtoupper($sheetName2), 'PENSIUN')) {
                    $rows2 = $sheet2->toArray();
                    $countPensiun = 0;

                    foreach ($rows2 as $row) {
                        $nama = trim((string)($row[1] ?? ''));
                        if (empty($nama) || str_contains(strtoupper($nama), 'NAMA') || str_contains(strtoupper($nama), 'PEMERINTAH')) continue;

                        $peg = Pegawai::where('nama', 'LIKE', "%{$nama}%")->first();
                        if ($peg) {
                            RiwayatPensiun::firstOrCreate(
                                ['pegawai_id' => $peg->id],
                                [
                                    'tanggal_pengajuan' => '2026-01-01',
                                    'tmt_pensiun' => '2027-01-01',
                                    'keterangan' => 'Diusulkan Pensiun Periode 2027',
                                ]
                            );
                            $countPensiun++;
                        }
                    }
                    $this->info("✓ Berhasil mengaitkan {$countPensiun} usulan pensiun.");
                }

                if (str_contains(strtoupper($sheetName2), 'KP 2026') || str_contains(strtoupper($sheetName2), 'PENGAJUAN KP')) {
                    $rowsKp = $sheet2->toArray();
                    $countKp = 0;

                    foreach ($rowsKp as $row) {
                        $nama = trim((string)($row[1] ?? ''));
                        $golLama = trim((string)($row[3] ?? ''));
                        $golBaru = trim((string)($row[4] ?? ''));

                        if (empty($nama) || str_contains(strtoupper($nama), 'NAMA') || str_contains(strtoupper($nama), 'PEMERINTAH')) continue;

                        $peg = Pegawai::where('nama', 'LIKE', "%{$nama}%")->first();
                        if ($peg) {
                            RiwayatKenaikanPangkat::firstOrCreate(
                                ['pegawai_id' => $peg->id],
                                [
                                    'golongan_lama' => $golLama ?: $peg->golongan,
                                    'golongan_baru' => $golBaru ?: 'IV/b',
                                    'tmt_diusulkan' => '2026-10-01',
                                    'keterangan' => 'Usulan Kenaikan Pangkat Periode 2026',
                                ]
                            );
                            $countKp++;
                        }
                    }
                    $this->info("✓ Berhasil mengaitkan {$countKp} usulan kenaikan pangkat.");
                }
            }

            $spreadsheet2->disconnectWorksheets();
            unset($spreadsheet2);
        }

        $this->info('🎉 SELAMAT! Seluruh data murni TAHUN 2026 berhasil diimpor ke database dispanperta!');
        return 0;
    }
}
