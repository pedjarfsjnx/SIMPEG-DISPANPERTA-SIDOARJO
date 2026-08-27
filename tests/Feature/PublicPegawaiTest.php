<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PublicPegawaiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email_verified_at' => now()]);
    }

    #[Test]
    public function halaman_utama_bisa_diakses_publik(): void
    {
        $this->get('/')->assertOk();
    }

    #[Test]
    public function index_publik_menampilkan_pegawai_tanpa_login(): void
    {
        $pegawai = Pegawai::factory()->create(['nama' => 'Rina Marlina']);

        $this->get(route('public.pegawai.index', ['search' => 'Rina']))
            ->assertOk()
            ->assertSee('Rina Marlina');
    }

    #[Test]
    public function filter_unit_kerja_mempersempit_hasil(): void
    {
        $unitA = UnitKerja::factory()->create(['nama' => 'Unit A']);
        $unitB = UnitKerja::factory()->create(['nama' => 'Unit B']);

        Pegawai::factory()->create(['nama' => 'Milik A', 'unit_kerja_id' => $unitA->id]);
        Pegawai::factory()->create(['nama' => 'Milik B', 'unit_kerja_id' => $unitB->id]);

        $response = $this->get(route('public.pegawai.index', ['unit_kerja_id' => $unitA->id]));

        $list = $response->viewData('pegawaiList');
        $this->assertTrue($list->contains('nama', 'Milik A'));
        $this->assertFalse($list->contains('nama', 'Milik B'));
    }

    #[Test]
    public function detail_pegawai_tampil(): void
    {
        $pegawai = Pegawai::factory()->create(['nama' => 'Dedi Kurniawan']);

        $this->get(route('public.pegawai.show', $pegawai->id))
            ->assertOk()
            ->assertSee('Dedi Kurniawan');
    }

    #[Test]
    public function detail_404_jika_tidak_ada(): void
    {
        $this->get(route('public.pegawai.show', 9999))->assertNotFound();
    }
}
