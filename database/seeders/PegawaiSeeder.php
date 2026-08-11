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
        $pns = KategoriPegawai::where('nama', 'PNS')->first();
        $pppk = KategoriPegawai::where('nama', 'PPPK')->first();
        $pppkParuh = KategoriPegawai::where('nama', 'PPPK Paruh Waktu')->first();
        $swakelola = KategoriPegawai::where('nama', 'Swakelola')->first();

        $aktif = StatusKepegawaian::where('nama', 'Aktif')->first();
        $dinasInduk = UnitKerja::where('tipe', 'Dinas Induk')->first();
        $uptdRph = UnitKerja::where('nama', 'LIKE', '%RPH%')->first();

        $sekretariat = Bidang::where('nama', 'Sekretariat')->first();
        $bidangPeternakan = Bidang::where('nama', 'LIKE', '%Peternakan%')->first();

        $formasiKadin = FormasiJabatan::where('nama_jabatan', 'KEPALA DINAS PANGAN DAN PERTANIAN')->first();
        $formasiSekdin = FormasiJabatan::where('nama_jabatan', 'SEKRETARIS DINAS')->first();
        $formasiKabidPeternakan = FormasiJabatan::where('nama_jabatan', 'KEPALA BIDANG PRODUKSI PETERNAKAN')->first();

        // Sample Pegawai 1 - PNS Kadin (dengan riwayat pensiun mendatang)
        $peg1 = Pegawai::create([
            'kategori_pegawai_id' => $pns?->id ?? 1,
            'status_kepegawaian_id' => $aktif?->id ?? 1,
            'unit_kerja_id' => $dinasInduk?->id ?? 1,
            'bidang_id' => null,
            'formasi_jabatan_id' => $formasiKadin?->id,
            'nama' => 'Ir. MOKHAMAD RUDY AL AMIN',
            'nip' => '196712101997032004',
            'nik' => null,
            'tempat_lahir' => 'Sidoarjo',
            'tanggal_lahir' => '1967-12-10',
            'pendidikan' => 'S2 Magister Pertanian',
            'golongan' => 'IV/c',
            'no_hp' => '081234567890',
            'email' => 'rudy.alamin@sidoarjo.go.id',
            'tmt_jabatan' => '2021-05-01',
        ]);

        RiwayatPensiun::create([
            'pegawai_id' => $peg1->id,
            'tanggal_pengajuan' => '2026-01-15',
            'tmt_pensiun' => '2027-01-01',
            'keterangan' => 'Diusulkan Pensiun BUP 2027',
        ]);

        // Sample Pegawai 2 - PNS Sekdin (dengan riwayat kenaikan pangkat)
        $peg2 = Pegawai::create([
            'kategori_pegawai_id' => $pns?->id ?? 1,
            'status_kepegawaian_id' => $aktif?->id ?? 1,
            'unit_kerja_id' => $dinasInduk?->id ?? 1,
            'bidang_id' => $sekretariat?->id,
            'formasi_jabatan_id' => $formasiSekdin?->id,
            'nama' => 'TONY HARTONO, SP, M.Si',
            'nip' => '197204151998031005',
            'nik' => null,
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1972-04-15',
            'pendidikan' => 'S2 Magister Sains',
            'golongan' => 'IV/a',
            'no_hp' => '081398765432',
            'email' => 'tony.hartono@sidoarjo.go.id',
            'tmt_jabatan' => '2022-09-10',
        ]);

        RiwayatKenaikanPangkat::create([
            'pegawai_id' => $peg2->id,
            'golongan_lama' => 'IV/a',
            'golongan_baru' => 'IV/b',
            'tmt_diusulkan' => '2026-10-01',
            'keterangan' => 'Usulan KP Periode Oktober 2026',
        ]);

        // Sample Pegawai 3 - PPPK
        Pegawai::create([
            'kategori_pegawai_id' => $pppk?->id ?? 2,
            'status_kepegawaian_id' => $aktif?->id ?? 1,
            'unit_kerja_id' => $dinasInduk?->id ?? 1,
            'bidang_id' => $bidangPeternakan?->id,
            'formasi_jabatan_id' => $formasiKabidPeternakan?->id,
            'nama' => 'SYAFI\'I, SP, M.Agr',
            'nip' => '198506202022211003',
            'nik' => null,
            'tempat_lahir' => 'Sidoarjo',
            'tanggal_lahir' => '1985-06-20',
            'pendidikan' => 'S2 Pertanian',
            'golongan' => 'IX',
            'no_hp' => '085712345678',
            'email' => 'syafii@sidoarjo.go.id',
            'tmt_jabatan' => '2023-01-01',
        ]);

        // Sample Pegawai 4 - Swakelola (kontrak pakai NIK)
        Pegawai::create([
            'kategori_pegawai_id' => $swakelola?->id ?? 4,
            'status_kepegawaian_id' => $aktif?->id ?? 1,
            'unit_kerja_id' => $uptdRph?->id ?? 2,
            'bidang_id' => null,
            'formasi_jabatan_id' => null,
            'nama' => 'BAMBANG KUSUMA',
            'nip' => null,
            'nik' => '3515081205900001',
            'tempat_lahir' => 'Sidoarjo',
            'tanggal_lahir' => '1990-05-12',
            'pendidikan' => 'SMA Sederajat',
            'golongan' => null,
            'no_hp' => '089611223344',
            'email' => 'bambang@gmail.com',
            'tmt_jabatan' => '2024-01-15',
        ]);
    }
}
