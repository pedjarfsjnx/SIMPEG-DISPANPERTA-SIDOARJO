<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminImportExportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email_verified_at' => now()]);
        UnitKerja::factory()->create();
    }

    #[Test]
    public function rute_impor_sudah_tidak_disediakan(): void
    {
        $this->actingAs($this->user)
            ->get('/admin/import')
            ->assertNotFound();
    }

    // ===== Export =====

    #[Test]
    public function export_excel_mengembalikan_file_xlsx(): void
    {
        Pegawai::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->get(route('admin.export.excel'));

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $contentDisposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('attachment', $contentDisposition);
        $this->assertStringContainsString('.xlsx', $contentDisposition);
    }

    #[Test]
    public function export_excel_hanya_menyertakan_data_sesuai_filter_search(): void
    {
        // Nama file menyertakan jumlah hasil: DATA_PEGAWAI_DISPANPERTA_(N_Data)_...
        Pegawai::factory()->create(['nama' => 'Budi Santoso']);
        Pegawai::factory()->create(['nama' => 'Siti Aminah']);

        $semua = $this->actingAs($this->user)->get(route('admin.export.excel'));
        $terfilter = $this->actingAs($this->user)
            ->get(route('admin.export.excel', ['search' => 'Budi']));

        $this->assertStringContainsString('(2_Data)', $semua->headers->get('Content-Disposition'));
        $this->assertStringContainsString('(1_Data)', $terfilter->headers->get('Content-Disposition'));
    }
}
