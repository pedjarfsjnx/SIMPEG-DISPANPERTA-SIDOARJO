<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\Bidang;
use App\Models\FormasiJabatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiAndPublicFeaturesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function api_force_sync_db_mengembalikan_json_sukses(): void
    {
        \Illuminate\Support\Facades\Artisan::shouldReceive('call')
            ->with('migrate', ['--force' => true])
            ->once()
            ->andReturn(0);

        \Illuminate\Support\Facades\Artisan::shouldReceive('call')
            ->with('db:seed', ['--force' => true])
            ->once()
            ->andReturn(0);

        $response = $this->getJson('/force-sync-db');

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
            ]);
    }

    #[Test]
    public function halaman_struktur_organisasi_bisa_diakses(): void
    {
        $unit = UnitKerja::factory()->create(['nama' => 'Dinas Induk', 'tipe' => 'dinas']);
        $bidang = Bidang::factory()->create(['unit_kerja_id' => $unit->id, 'nama' => 'Bidang Tanaman']);
        $formasi = FormasiJabatan::factory()->create([
            'unit_kerja_id' => $unit->id,
            'bidang_id' => $bidang->id,
            'nama_jabatan' => 'Kepala Bidang',
        ]);
        Pegawai::factory()->create([
            'unit_kerja_id' => $unit->id,
            'bidang_id' => $bidang->id,
            'formasi_jabatan_id' => $formasi->id,
            'nama' => 'Pejabat Struktural',
        ]);

        $response = $this->get(route('public.struktur-organisasi'));

        $response->assertOk()
            ->assertSee('Struktur Organisasi')
            ->assertSee('Bidang Tanaman');
    }

    #[Test]
    public function halaman_cetak_pegawai_bisa_diakses(): void
    {
        Pegawai::factory()->create(['nama' => 'Staf Cetak']);

        $response = $this->get(route('public.pegawai.cetak'));

        $response->assertOk()
            ->assertSee('Staf Cetak');
    }

    #[Test]
    public function download_pdf_pegawai_menghasilkan_response_pdf_atau_html(): void
    {
        Pegawai::factory()->create(['nama' => 'Staf PDF']);

        $response = $this->get(route('public.pegawai.download-pdf'));

        $response->assertOk();
    }
}
