<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPegawai;
use App\Models\StatusKepegawaian;
use App\Models\UnitKerja;
use App\Models\Bidang;
use App\Models\FormasiJabatan;
use App\Models\Pegawai;
use App\Models\RiwayatPensiun;
use App\Models\RiwayatKenaikanPangkat;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = __DIR__ . '/pegawai_data.json';
        if (!file_exists($jsonPath)) {
            return;
        }

        $pegawaiList = json_decode(file_get_contents($jsonPath), true);
        if (empty($pegawaiList)) {
            return;
        }

        // Clean tables to avoid duplicates on re-seed
        RiwayatPensiun::query()->delete();
        RiwayatKenaikanPangkat::query()->delete();
        Pegawai::query()->forceDelete();

        foreach ($pegawaiList as $item) {
            $kategori = KategoriPegawai::firstOrCreate(['nama' => $item['kategori_nama'] ?? 'PNS']);
            $status = StatusKepegawaian::firstOrCreate(['nama' => $item['status_nama'] ?? 'Aktif']);
            $unitKerja = UnitKerja::firstOrCreate(
                ['nama' => $item['unit_kerja_nama'] ?? 'Dinas Pangan dan Pertanian (Induk)'],
                ['tipe' => str_contains($item['unit_kerja_nama'] ?? '', 'UPTD') ? 'UPTD' : 'Dinas Induk']
            );

            $bidangId = null;
            if (!empty($item['bidang_nama'])) {
                $bidang = Bidang::firstOrCreate(
                    ['unit_kerja_id' => $unitKerja->id, 'nama' => $item['bidang_nama']],
                    ['aktif' => true]
                );
                $bidangId = $bidang->id;
            }

            $formasiId = null;
            if (!empty($item['jabatan_nama'])) {
                $formasi = FormasiJabatan::firstOrCreate(
                    ['nama_jabatan' => $item['jabatan_nama']],
                    [
                        'unit_kerja_id' => $unitKerja->id,
                        'bidang_id' => $bidangId,
                        'kelas_jabatan' => $item['kelas_jabatan'] ?? 7,
                        'jenis_jabatan' => $item['jenis_jabatan'] ?? 'Pelaksana',
                        'kuota' => 1,
                        'status_formasi' => 'terisi'
                    ]
                );
                $formasiId = $formasi->id;
            }

            $pegawai = Pegawai::create([
                'kategori_pegawai_id' => $kategori->id,
                'status_kepegawaian_id' => $status->id,
                'unit_kerja_id' => $unitKerja->id,
                'bidang_id' => $bidangId,
                'formasi_jabatan_id' => $formasiId,
                'nama' => $item['nama'],
                'nip' => $item['nip'] ?: null,
                'nik' => $item['nik'] ?: null,
                'tempat_lahir' => $item['tempat_lahir'] ?: null,
                'tanggal_lahir' => $item['tanggal_lahir'] ?: null,
                'pendidikan' => $item['pendidikan'] ?: null,
                'golongan' => $item['golongan'] ?: null,
                'no_hp' => $item['no_hp'] ?: null,
                'email' => $item['email'] ?: null,
                'tmt_jabatan' => $item['tmt_jabatan'] ?: null,
            ]);

            if (!empty($item['pensiun'])) {
                foreach ($item['pensiun'] as $pen) {
                    RiwayatPensiun::create([
                        'pegawai_id' => $pegawai->id,
                        'tanggal_pengajuan' => $pen['tanggal_pengajuan'] ?: null,
                        'tmt_pensiun' => $pen['tmt_pensiun'] ?: null,
                        'keterangan' => $pen['keterangan'] ?: null,
                    ]);
                }
            }

            if (!empty($item['kp'])) {
                foreach ($item['kp'] as $kpItem) {
                    RiwayatKenaikanPangkat::create([
                        'pegawai_id' => $pegawai->id,
                        'golongan_lama' => $kpItem['golongan_lama'] ?: null,
                        'golongan_baru' => $kpItem['golongan_baru'] ?: null,
                        'tmt_diusulkan' => $kpItem['tmt_diusulkan'] ?: null,
                        'keterangan' => $kpItem['keterangan'] ?: null,
                    ]);
                }
            }
        }
    }
}
