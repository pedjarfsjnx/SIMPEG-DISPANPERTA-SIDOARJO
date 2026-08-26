<?php

namespace Tests\Unit;

use App\Models\Pegawai;
use Database\Factories\FormasiJabatanFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PegawaiTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function makePegawai(array $attributes = []): Pegawai
    {
        return Pegawai::factory()->create($attributes);
    }

    // ===== is_pns =====

    #[Test]
    public function pegawai_dengan_kategori_pns_dan_golongan_dianggap_pns(): void
    {
        $pegawai = $this->makePegawai(['golongan' => 'III/c']);

        $this->assertTrue($pegawai->is_pns);
    }

    #[Test]
    public function pegawai_pppk_bukan_pns(): void
    {
        $pegawai = Pegawai::factory()->pppk()->create();

        $this->assertFalse($pegawai->is_pns);
        $this->assertTrue($pegawai->is_pppk);
    }

    #[Test]
    public function pegawai_honorer_bukan_pns(): void
    {
        $pegawai = Pegawai::factory()->honorer()->create();

        $this->assertFalse($pegawai->is_pns);
        $this->assertFalse($pegawai->is_pppk);
    }

    #[Test]
    public function golongan_kosong_atau_strip_membuat_bukan_pns(): void
    {
        foreach (['-', '', null] as $golongan) {
            $pegawai = $this->makePegawai(['golongan' => $golongan]);
            $this->assertFalse($pegawai->is_pns, "Golongan '{$golongan}' seharusnya bukan PNS");
        }
    }

    // ===== tanggal_lahir_effektif & usia =====

    #[Test]
    public function tanggal_lahir_effektif_pakai_kolom_tanggal_lahir(): void
    {
        $pegawai = $this->makePegawai([
            'tanggal_lahir' => '1992-07-10',
            'nip' => '198503122010011001',
        ]);

        $this->assertSame('1992-07-10', $pegawai->tanggal_lahir_effektif->toDateString());
    }

    #[Test]
    public function tanggal_lahir_effektif_fallback_ke_nip(): void
    {
        $pegawai = $this->makePegawai([
            'tanggal_lahir' => null,
            'nip' => '198503122010011001',
        ]);

        $this->assertSame('1985-03-12', $pegawai->tanggal_lahir_effektif->toDateString());
    }

    #[Test]
    public function tanggal_lahir_effektif_null_jika_tidak_ada_sumber(): void
    {
        $pegawai = $this->makePegawai(['tanggal_lahir' => null, 'nip' => null]);

        $this->assertNull($pegawai->tanggal_lahir_effektif);
    }

    #[Test]
    public function nip_format_anak_rampan_tetap_null(): void
    {
        $pegawai = $this->makePegawai(['tanggal_lahir' => null, 'nip' => 'ABCD']);

        $this->assertNull($pegawai->tanggal_lahir_effektif);
    }

    #[Test]
    public function usia_dihitung_dari_tanggal_lahir(): void
    {
        Carbon::setTestNow('2026-08-26');

        $pegawai = $this->makePegawai(['tanggal_lahir' => '1990-05-15', 'nip' => null]);

        $this->assertSame(36, $pegawai->usia);
    }

    #[Test]
    public function usia_null_tanpa_tanggal_lahir(): void
    {
        $pegawai = $this->makePegawai(['tanggal_lahir' => null, 'nip' => null]);

        $this->assertNull($pegawai->usia);
    }

    // ===== batas_usia_pensiun =====

    #[Test]
    public function kepala_dinas_pensiun_umur_60(): void
    {
        $formasi = FormasiJabatanFactory::new()->create([
            'nama_jabatan' => 'Kepala Dinas',
            'kelas_jabatan' => '17',
        ]);

        $pegawai = $this->makePegawai(['formasi_jabatan_id' => $formasi->id]);

        $this->assertSame(60, $pegawai->batas_usia_pensiun);
    }

    #[Test]
    public function kelas_jabatan_14_keatas_pensiun_umur_60(): void
    {
        $formasi = FormasiJabatanFactory::new()->create([
            'nama_jabatan' => 'Sekretaris',
            'kelas_jabatan' => '14',
        ]);

        $pegawai = $this->makePegawai(['formasi_jabatan_id' => $formasi->id]);

        $this->assertSame(60, $pegawai->batas_usia_pensiun);
    }

    #[Test]
    public function fungsional_madya_pensiun_umur_60(): void
    {
        $formasi = FormasiJabatanFactory::new()->create([
            'nama_jabatan' => 'Analis Kebijakan Ahli Madya',
            'kelas_jabatan' => '13',
        ]);

        $pegawai = $this->makePegawai(['formasi_jabatan_id' => $formasi->id]);

        $this->assertSame(60, $pegawai->batas_usia_pensiun);
    }

    #[Test]
    public function pelaksana_pertama_dan_terampil_pensiun_umur_58(): void
    {
        foreach (
            [
                ['nama_jabatan' => 'Pelaksana', 'kelas_jabatan' => '3'],
                ['nama_jabatan' => 'Analis Agama Ahli Pertama', 'kelas_jabatan' => '7'],
                ['nama_jabatan' => 'Penyuluh Pertanian Terampil', 'kelas_jabatan' => '5'],
            ] as $jabatan
        ) {
            $formasi = FormasiJabatanFactory::new()->create($jabatan);
            $pegawai = $this->makePegawai(['formasi_jabatan_id' => $formasi->id]);
            $this->assertSame(58, $pegawai->batas_usia_pensiun, $jabatan['nama_jabatan']);
        }
    }

    // ===== estimasi_pensiun =====

    #[Test]
    public function estimasi_pensiun_adalah_ulang_tahun_plus_end_of_month(): void
    {
        Carbon::setTestNow('2026-08-26');

        $formasi = FormasiJabatanFactory::new()->create([
            'nama_jabatan' => 'Pelaksana',
            'kelas_jabatan' => '3',
        ]);
        $pegawai = $this->makePegawai([
            'tanggal_lahir' => '1970-02-10',
            'formasi_jabatan_id' => $formasi->id,
        ]);

        $estimasi = $pegawai->estimasi_pensiun;

        $this->assertSame(58, $estimasi['usia']);
        $this->assertSame('2028-02-29', $estimasi['tanggal']->toDateString()); // end of month, tahun kabisat
    }

    #[Test]
    public function estimasi_pensiun_tanggal_null_tanpa_tanggal_lahir(): void
    {
        $pegawai = $this->makePegawai(['tanggal_lahir' => null, 'nip' => null]);

        $estimasi = $pegawai->estimasi_pensiun;

        $this->assertNull($estimasi['tanggal']);
        $this->assertSame($pegawai->batas_usia_pensiun, $estimasi['usia']);
    }

    // ===== estimasi_kp_berikutnya =====

    #[Test]
    public function bukan_pns_tidak_ada_estimasi_kp(): void
    {
        Carbon::setTestNow('2026-08-26');

        $pegawai = Pegawai::factory()->pppk()->create([
            'tmt_jabatan' => '2020-01-01',
            'golongan' => null,
        ]);

        $this->assertNull($pegawai->estimasi_kp_berikutnya);
    }

    #[Test]
    public function pns_tanpa_tmt_jabatan_tidak_ada_estimasi_kp(): void
    {
        $pegawai = $this->makePegawai(['tmt_jabatan' => null]);

        $this->assertNull($pegawai->estimasi_kp_berikutnya);
    }

    #[Test]
    public function kp_berikutnya_snap_ke_periode_bkn_terdekat(): void
    {
        Carbon::setTestNow('2026-08-26');

        // TMT 2023-09-01 -> +4 thn = 2027-09-01 (masa depan) -> periode BKN >= Sep = Okt (bulan 10)
        $pegawai = $this->makePegawai([
            'tmt_jabatan' => '2023-09-01',
            'tanggal_lahir' => '1990-05-15', // pensiun 2048, jauh setelah KP
        ]);

        $this->assertSame('2027-10-01', $pegawai->estimasi_kp_berikutnya->toDateString());
    }

    #[Test]
    public function kp_berikutnya_melompat_4_tahun_selama_tmt_masih_lampau(): void
    {
        Carbon::setTestNow('2026-08-26');

        // TMT 2015-03-01 -> +12 thn = 2027-03-01 (masa depan) -> periode BKN >= Mar = Apr (bulan 4)
        $pegawai = $this->makePegawai([
            'tmt_jabatan' => '2015-03-01',
            'tanggal_lahir' => '1990-05-15',
        ]);

        $this->assertSame('2027-04-01', $pegawai->estimasi_kp_berikutnya->toDateString());
    }

    #[Test]
    public function kp_setelah_batas_pensiun_return_null(): void
    {
        Carbon::setTestNow('2026-08-26');

        // Lahir 1969-01-20, pelaksana (58) -> pensiun 2027-01-31.
        // TMT 2024-06-01 -> +4 thn = 2028-06-01 > pensiun -> null
        $formasi = FormasiJabatanFactory::new()->create([
            'nama_jabatan' => 'Pelaksana',
            'kelas_jabatan' => '3',
        ]);
        $pegawai = $this->makePegawai([
            'tmt_jabatan' => '2024-06-01',
            'tanggal_lahir' => '1969-01-20',
            'formasi_jabatan_id' => $formasi->id,
        ]);

        $this->assertNull($pegawai->estimasi_kp_berikutnya);
    }
}
