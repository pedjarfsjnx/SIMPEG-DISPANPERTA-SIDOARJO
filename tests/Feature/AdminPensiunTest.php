<?php

namespace Tests\Feature;

use App\Models\FormasiJabatan;
use App\Models\Pegawai;
use App\Models\RiwayatPensiun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminPensiunTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email_verified_at' => now()]);
        Carbon::setTestNow('2026-08-26');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * Pegawai pelaksana (BUP 58) dengan tanggal lahir eksplisit.
     */
    private function pegawaiDenganPensiun(string $tanggalLahir): Pegawai
    {
        $formasi = FormasiJabatan::factory()->create([
            'nama_jabatan' => 'Pelaksana',
            'kelas_jabatan' => '3',
        ]);

        return Pegawai::factory()->pns()->create([
            'tanggal_lahir' => $tanggalLahir,
            'nip' => null,
            'formasi_jabatan_id' => $formasi->id,
        ]);
    }

    // ===== Index / Rekap =====

    #[Test]
    public function rekap_tampil_dengan_statistik_ringkasan(): void
    {
        $this->pegawaiDenganPensiun('1968-05-20'); // pensiun 2026-05-31 (tahun ini, sudah lewat)
        $this->pegawaiDenganPensiun('1970-02-10'); // pensiun 2028-02-29
        $this->pegawaiDenganPensiun('1969-06-15'); // pensiun 2027-06-30
        Pegawai::factory()->honorer()->create(['tanggal_lahir' => null]); // tanpa sumber tgl lahir -> di-skip

        $response = $this->actingAs($this->user)->get(route('admin.pensiun.index'));

        $response->assertOk();

        $this->assertSame(3, $response->viewData('totalPNS'), 'Honorer tanpa tgl lahir tidak dihitung');
        $this->assertSame(1, $response->viewData('pensiunTahunIni'));   // 2026
        $this->assertSame(1, $response->viewData('pensiunTahunDepan')); // 2027

        $list = $response->viewData('pensiunList');
        $this->assertSame(3, $list->count());
        $this->assertSame(
            ['2026-05-31', '2027-06-30', '2028-02-29'],
            array_map(fn ($item) => $item->tmt_pensiun->toDateString(), $list->items()),
            'Urutan TMT terdekat dulu'
        );
    }

    #[Test]
    public function filter_bulan_menyaring_rekap(): void
    {
        $this->pegawaiDenganPensiun('1970-02-10'); // Februari 2028
        $this->pegawaiDenganPensiun('1969-06-15'); // Juni 2027

        $list = $this->actingAs($this->user)
            ->get(route('admin.pensiun.index', ['bulan' => 6]))
            ->viewData('pensiunList');

        $this->assertSame(1, $list->count());
        $this->assertSame(6, (int) $list[0]->tmt_pensiun->format('m'));
    }

    #[Test]
    public function filter_tahun_menyaring_rekap(): void
    {
        $this->pegawaiDenganPensiun('1970-02-10'); // 2028
        $this->pegawaiDenganPensiun('1969-06-15'); // 2027

        $list = $this->actingAs($this->user)
            ->get(route('admin.pensiun.index', ['tahun' => 2028]))
            ->viewData('pensiunList');

        $this->assertSame(1, $list->count());
        $this->assertSame(2028, $list[0]->tahun);
    }

    #[Test]
    public function sisa_waktu_status_purna_tugas_jika_sudah_lewat(): void
    {
        Carbon::setTestNow('2026-08-26');

        $formasi = FormasiJabatan::factory()->create([
            'nama_jabatan' => 'Pelaksana',
            'kelas_jabatan' => '3',
        ]);
        Pegawai::factory()->pns()->create([
            'tanggal_lahir' => '1967-01-01', // BUP 58 -> pensiun 2025-01-31 (lewat)
            'nip' => null,
            'formasi_jabatan_id' => $formasi->id,
        ]);

        $list = $this->actingAs($this->user)
            ->get(route('admin.pensiun.index'))
            ->viewData('pensiunList');

        $this->assertSame('Purna Tugas', $list[0]->sisa_waktu);
    }

    // ===== Store & Destroy Riwayat =====

    #[Test]
    public function store_riwayat_pensiun_valid(): void
    {
        $pegawai = Pegawai::factory()->create();

        $this->actingAs($this->user)
            ->post(route('admin.pensiun.store'), [
                'pegawai_id' => $pegawai->id,
                'tanggal_pengajuan' => '2026-08-01',
                'tmt_pensiun' => '2028-03-01',
                'keterangan' => 'Usulan reguler',
            ])
            ->assertRedirect(route('admin.pensiun.index'))
            ->assertSessionHas('success');

        $riwayat = RiwayatPensiun::where('pegawai_id', $pegawai->id)->first();
        $this->assertNotNull($riwayat);
        $this->assertSame('2028-03-01', $riwayat->tmt_pensiun->toDateString());
    }

    #[Test]
    public function store_validasi_wajib_isi(): void
    {
        $this->actingAs($this->user)
            ->post(route('admin.pensiun.store'), [])
            ->assertSessionHasErrors(['pegawai_id', 'tmt_pensiun']);

        $this->assertDatabaseCount('riwayat_pensiun', 0);
    }

    #[Test]
    public function destroy_menghapus_riwayat_pensiun(): void
    {
        $riwayat = RiwayatPensiun::create([
            'pegawai_id' => Pegawai::factory()->create()->id,
            'tmt_pensiun' => '2028-03-01',
        ]);

        $this->actingAs($this->user)
            ->delete(route('admin.pensiun.destroy', $riwayat))
            ->assertRedirect(route('admin.pensiun.index'));

        $this->assertDatabaseMissing('riwayat_pensiun', ['id' => $riwayat->id]);
    }
}
