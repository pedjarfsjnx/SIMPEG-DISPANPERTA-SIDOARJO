<?php

namespace Tests\Feature;

use App\Models\FormasiJabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Factories\KategoriPegawaiFactory;
use Database\Factories\StatusKepegawaianFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminImportExportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private const XLSX_MIME = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email_verified_at' => now()]);
        UnitKerja::factory()->create();
    }

    private function createExcelFile(array $rows): UploadedFile
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($rows as $rIndex => $row) {
            foreach ($row as $cIndex => $value) {
                $sheet->setCellValue([$cIndex + 1, $rIndex + 1], $value);
            }
        }
        $path = tempnam(sys_get_temp_dir(), 'import_test').'.xlsx';
        (new Xlsx($spreadsheet))->save($path);

        return new UploadedFile($path, 'data_pegawai.xlsx', self::XLSX_MIME, null, true);
    }

    // ===== Form & Validasi =====

    #[Test]
    public function form_impor_tampil_untuk_admin(): void
    {
        $this->actingAs($this->user)
            ->get(route('admin.import.form'))
            ->assertOk();
    }

    #[Test]
    public function tanpa_file_ditolak_validasi(): void
    {
        $this->actingAs($this->user)
            ->post(route('admin.import.preview'), [])
            ->assertSessionHasErrors(['excel_file']);
    }

    #[Test]
    public function format_file_tidak_didukung_ditolak(): void
    {
        $file = UploadedFile::fake()->create('data.pdf', 10, 'application/pdf');

        $this->actingAs($this->user)
            ->post(route('admin.import.preview'), ['excel_file' => $file])
            ->assertSessionHasErrors(['excel_file']);
    }

    // ===== Preview =====

    #[Test]
    public function preview_mem_parse_baris_data_melewati_kop_dan_header(): void
    {
        $file = $this->createExcelFile([
            ['PEMERINTAH KABUPATEN SIDOARJO', '', '', '', ''],
            ['DINAS PANGAN DAN PERTANIAN', '', '', '', ''],
            ['NAMA LENGKAP', 'NIP', 'JABATAN', 'GOLONGAN', 'PENDIDIKAN'],
            ['Budi Santoso', "'198503122010011001", 'Analis Kebijakan', 'III/c', 'S1'],
            ['Siti Aminah', '199001012014021002', 'Penyuluh Pertanian', 'II/b', 'S1'],
            ['', '', '', '', ''],
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('admin.import.preview'), ['excel_file' => $file]);

        $response->assertOk();
        $preview = session('import_preview_data');
        $this->assertCount(2, $preview);
        $this->assertSame('Budi Santoso', $preview[0]['nama']);
        $this->assertSame('198503122010011001', $preview[0]['nip'], 'Apostrof NIP harus dibersihkan');
        $this->assertSame('III/c', $preview[0]['golongan']);
        $this->assertSame('Siti Aminah', $preview[1]['nama']);
    }

    // ===== Commit =====

    #[Test]
    public function commit_tanpa_session_data_redirect_error(): void
    {
        $this->actingAs($this->user)
            ->post(route('admin.import.commit'))
            ->assertRedirect(route('admin.import.form'))
            ->assertSessionHas('error');
    }

    #[Test]
    public function commit_membuat_pegawai_dengan_default_master_data(): void
    {
        KategoriPegawaiFactory::pns();
        StatusKepegawaianFactory::aktif();

        $this->session(['import_preview_data' => [
            [
                'nama' => 'Budi Santoso',
                'nip' => '198503122010011001',
                'jabatan' => 'Analis Kebijakan',
                'golongan' => 'III/c',
                'pendidikan' => 'S1',
            ],
            ['nama' => '', 'nip' => '', 'jabatan' => '', 'golongan' => '', 'pendidikan' => ''],
        ]]);

        $response = $this->actingAs($this->user)->post(route('admin.import.commit'));

        $response->assertRedirect(route('admin.pegawai.index'))
            ->assertSessionHas('success');

        $pegawai = Pegawai::where('nama', 'Budi Santoso')->first();
        $this->assertNotNull($pegawai);
        $this->assertSame('198503122010011001', $pegawai->nip);
        $this->assertSame('PNS', $pegawai->kategori->nama, 'Default kategori PNS');
        $this->assertSame('Aktif', $pegawai->status->nama, 'Default status Aktif');
        $this->assertSame(1, Pegawai::count(), 'Baris nama kosong harus dilewati');
        $this->assertNull(session('import_preview_data'), 'Session preview harus dibersihkan');
    }

    #[Test]
    public function commit_membuat_formasi_jabatan_jika_belum_ada(): void
    {
        KategoriPegawaiFactory::pns();
        StatusKepegawaianFactory::aktif();

        $this->session(['import_preview_data' => [
            ['nama' => 'Rina Marlina', 'nip' => null, 'jabatan' => 'Penyuluh Pertanian', 'golongan' => null, 'pendidikan' => null],
        ]]);

        $this->actingAs($this->user)->post(route('admin.import.commit'));

        $formasi = FormasiJabatan::where('nama_jabatan', 'Penyuluh Pertanian')->first();
        $this->assertNotNull($formasi, 'Formasi harus firstOrCreate dari kolom jabatan');
        $this->assertSame($formasi->id, Pegawai::where('nama', 'Rina Marlina')->first()->formasi_jabatan_id);
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
