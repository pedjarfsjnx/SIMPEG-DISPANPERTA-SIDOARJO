<?php

namespace Tests\Feature;

use App\Models\FormasiJabatan;
use App\Models\Pegawai;
use App\Models\RiwayatKenaikanPangkat;
use App\Models\RiwayatPensiun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminKenaikanPangkatTest extends TestCase
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

    private function pnsAktif(array $attributes = []): Pegawai
    {
        $formasi = FormasiJabatan::factory()->create([
            'nama_jabatan' => 'Pelaksana',
            'kelas_jabatan' => '3',
        ]);

        return Pegawai::factory()->pns()->create(array_merge([
            'tanggal_lahir' => '1990-05-15',
            'nip' => '199005152010011001',
            'golongan' => 'III/a',
            'tmt_jabatan' => '2022-04-01',
            'formasi_jabatan_id' => $formasi->id,
        ], $attributes));
    }

    // ===== Index =====

    #[Test]
    public function index_menampilkan_usulan_kp(): void
    {
        $pegawai = $this->pnsAktif();
        RiwayatKenaikanPangkat::create([
            'pegawai_id' => $pegawai->id,
            'golongan_lama' => 'III/a',
            'golongan_baru' => 'III/b',
            'tmt_diusulkan' => '2026-10-01',
        ]);

        $response = $this->actingAs($this->user)->get(route('admin.kenaikan-pangkat.index'));

        $response->assertOk();
        $this->assertSame(1, $response->viewData('kpList')->count());
        $this->assertSame(1, $response->viewData('totalUsulan'));
    }

    #[Test]
    public function index_pencarian_by_nama(): void
    {
        $budi = $this->pnsAktif(['nama' => 'Budi Santoso']);
        $siti = $this->pnsAktif(['nama' => 'Siti Aminah']);
        foreach ([$budi, $siti] as $p) {
            RiwayatKenaikanPangkat::create([
                'pegawai_id' => $p->id,
                'golongan_baru' => 'III/b',
                'tmt_diusulkan' => '2026-10-01',
            ]);
        }

        $kpList = $this->actingAs($this->user)
            ->get(route('admin.kenaikan-pangkat.index', ['search' => 'Siti']))
            ->viewData('kpList');

        $this->assertSame(1, $kpList->count());
    }

    // ===== Create: penyaringan kandidat =====

    #[Test]
    public function create_hanya_menampilkan_pns_belum_pensiun(): void
    {
        $layak = $this->pnsAktif(['nama' => 'Layak Usul']);
        $pppk = Pegawai::factory()->pppk()->create(['nama' => 'Adik PPPK']);
        $honorer = Pegawai::factory()->honorer()->create(['nama' => 'Anak Honorer']);

        $formasiMadya = FormasiJabatan::factory()->create([
            'nama_jabatan' => 'Analis Ahli Madya',
            'kelas_jabatan' => '13',
        ]);
        $bupLewat = Pegawai::factory()->pns()->create([
            'nama' => 'Sudah Purna BUP',
            'tanggal_lahir' => '1964-01-01', // BUP 60 (madya) -> 2024-01-31 lewat
            'formasi_jabatan_id' => $formasiMadya->id,
        ]);

        $purnaResmi = $this->pnsAktif(['nama' => 'Purna Resmi']);
        RiwayatPensiun::create([
            'pegawai_id' => $purnaResmi->id,
            'tmt_pensiun' => '2025-01-01', // lewat dari sekarang
        ]);

        $names = $this->actingAs($this->user)
            ->get(route('admin.kenaikan-pangkat.create'))
            ->viewData('pegawaiList')
            ->pluck('nama')
            ->all();

        $this->assertContains('Layak Usul', $names);
        $this->assertNotContains('Adik PPPK', $names);
        $this->assertNotContains('Anak Honorer', $names);
        $this->assertNotContains('Sudah Purna BUP', $names);
        $this->assertNotContains('Purna Resmi', $names);
    }

    // ===== Store =====

    #[Test]
    public function store_usulan_kp_valid(): void
    {
        $pegawai = $this->pnsAktif();

        $this->actingAs($this->user)
            ->post(route('admin.kenaikan-pangkat.store'), [
                'pegawai_id' => $pegawai->id,
                'golongan_lama' => 'III/a',
                'golongan_baru' => 'III/b',
                'tmt_diusulkan' => '2026-10-01',
            ])
            ->assertRedirect(route('admin.kenaikan-pangkat.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('riwayat_kenaikan_pangkat', [
            'pegawai_id' => $pegawai->id,
            'golongan_baru' => 'III/b',
        ]);
    }

    #[Test]
    public function store_tolak_usulan_setelah_bup_estimasi(): void
    {
        // Lahir 1969-01-20, pelaksana (58) -> BUP 2027-01-31
        $formasi = FormasiJabatan::factory()->create([
            'nama_jabatan' => 'Pelaksana',
            'kelas_jabatan' => '3',
        ]);
        $pegawai = $this->pnsAktif([
            'tanggal_lahir' => '1969-01-20',
            'formasi_jabatan_id' => $formasi->id,
        ]);

        $this->actingAs($this->user)
            ->post(route('admin.kenaikan-pangkat.store'), [
                'pegawai_id' => $pegawai->id,
                'golongan_baru' => 'III/b',
                'tmt_diusulkan' => '2027-06-01', // setelah BUP
            ])
            ->assertSessionHasErrors('tmt_diusulkan');

        $this->assertDatabaseCount('riwayat_kenaikan_pangkat', 0);
    }

    #[Test]
    public function store_tolak_usulan_setelah_pensiun_resmi(): void
    {
        $pegawai = $this->pnsAktif();
        RiwayatPensiun::create([
            'pegawai_id' => $pegawai->id,
            'tmt_pensiun' => '2027-04-01',
        ]);

        $this->actingAs($this->user)
            ->post(route('admin.kenaikan-pangkat.store'), [
                'pegawai_id' => $pegawai->id,
                'golongan_baru' => 'III/b',
                'tmt_diusulkan' => '2027-10-01', // setelah TMT pensiun resmi
            ])
            ->assertSessionHasErrors('tmt_diusulkan');

        $this->assertDatabaseCount('riwayat_kenaikan_pangkat', 0);
    }

    #[Test]
    public function store_boleh_sebelum_pensiun_resmi(): void
    {
        $pegawai = $this->pnsAktif();
        RiwayatPensiun::create([
            'pegawai_id' => $pegawai->id,
            'tmt_pensiun' => '2048-05-31',
        ]);

        $this->actingAs($this->user)
            ->post(route('admin.kenaikan-pangkat.store'), [
                'pegawai_id' => $pegawai->id,
                'golongan_baru' => 'III/b',
                'tmt_diusulkan' => '2026-10-01',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('riwayat_kenaikan_pangkat', 1);
    }

    #[Test]
    public function destroy_menghapus_usulan(): void
    {
        $usulan = RiwayatKenaikanPangkat::create([
            'pegawai_id' => $this->pnsAktif()->id,
            'golongan_baru' => 'III/b',
            'tmt_diusulkan' => '2026-10-01',
        ]);

        $this->actingAs($this->user)
            ->delete(route('admin.kenaikan-pangkat.destroy', $usulan))
            ->assertRedirect(route('admin.kenaikan-pangkat.index'));

        $this->assertDatabaseMissing('riwayat_kenaikan_pangkat', ['id' => $usulan->id]);
    }
}
