<?php

namespace Tests\Feature;

use App\Models\FormasiJabatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminPegawaiCrudTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUser(): User
    {
        return User::factory()->create(['email_verified_at' => now()]);
    }

    #[Test]
    public function tamu_diarahkan_ke_login(): void
    {
        $this->get(route('admin.pegawai.index'))->assertRedirect('/login');
        $this->post(route('admin.pegawai.store'), [])->assertRedirect('/login');
    }

    #[Test]
    public function index_menampilkan_daftar_pegawai(): void
    {
        $pegawai = Pegawai::factory()->create(['nama' => 'Budi Santoso']);

        $response = $this->actingAs($this->verifiedUser())->get(route('admin.pegawai.index'));

        $response->assertOk()->assertSee('Budi Santoso');
        $this->assertTrue($response->viewData('pegawaiList')->contains('id', $pegawai->id));
    }

    #[Test]
    public function index_menyembunyikan_pegawai_terhapus(): void
    {
        $aktif = Pegawai::factory()->create(['nama' => 'Masih Aktif']);
        $terhapus = Pegawai::factory()->create(['nama' => 'Sudah Dihapus']);
        $terhapus->delete();

        $response = $this->actingAs($this->verifiedUser())
            ->get(route('admin.pegawai.index', ['trashed' => 'only']));

        $this->assertTrue($response->viewData('pegawaiList')->contains('id', $terhapus->id));
        $this->assertFalse($response->viewData('pegawaiList')->contains('id', $aktif->id));
    }

    #[Test]
    public function pencarian_nama_nip_nik_bekerja(): void
    {
        Pegawai::factory()->create(['nama' => 'Andi Wijaya', 'nip' => '198503122010011001']);
        Pegawai::factory()->create(['nama' => 'Siti Aminah', 'nip' => '199001012014021002']);

        $byNama = $this->actingAs($this->verifiedUser())
            ->get(route('admin.pegawai.index', ['search' => 'Siti']));
        $this->assertSame(1, $byNama->viewData('pegawaiList')->count());

        $byNip = $this->actingAs($this->verifiedUser())
            ->get(route('admin.pegawai.index', ['search' => '19900101']));
        $this->assertTrue($byNip->viewData('pegawaiList')->contains('nama', 'Siti Aminah'));
    }

    #[Test]
    public function store_menyimpan_pegawai_baru_dan_log_aktivitas(): void
    {
        $user = $this->verifiedUser();
        $payload = Pegawai::factory()->make()->toArray();

        $response = $this->actingAs($user)->post(route('admin.pegawai.store'), $payload);

        $response->assertRedirect(route('admin.pegawai.index'));
        $this->assertDatabaseHas('pegawai', [
            'nama' => $payload['nama'],
            'nip' => $payload['nip'],
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE',
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function store_set_formasi_jadi_terisi(): void
    {
        $formasi = FormasiJabatan::factory()->create(['status_formasi' => 'kosong']);
        $payload = array_merge(Pegawai::factory()->make()->toArray(), [
            'formasi_jabatan_id' => $formasi->id,
        ]);

        $this->actingAs($this->verifiedUser())->post(route('admin.pegawai.store'), $payload);

        $this->assertSame('terisi', $formasi->fresh()->status_formasi);
    }

    #[Test]
    public function store_validasi_gagal_jika_nama_kosong(): void
    {
        $payload = array_merge(Pegawai::factory()->make()->toArray(), ['nama' => '']);

        $response = $this->actingAs($this->verifiedUser())
            ->post(route('admin.pegawai.store'), $payload);

        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseCount('pegawai', 0);
    }

    #[Test]
    public function store_tolak_nip_duplikat(): void
    {
        $existing = Pegawai::factory()->create();
        $payload = array_merge(Pegawai::factory()->make()->toArray(), [
            'nip' => $existing->nip,
        ]);

        $this->actingAs($this->verifiedUser())
            ->post(route('admin.pegawai.store'), $payload)
            ->assertSessionHasErrors(['nip']);
    }

    #[Test]
    public function update_mengubah_data_pegawai(): void
    {
        $pegawai = Pegawai::factory()->create();

        $this->actingAs($this->verifiedUser())
            ->put(route('admin.pegawai.update', $pegawai), array_merge(
                Pegawai::factory()->make()->toArray(),
                ['nama' => 'Nama Baru']
            ))
            ->assertRedirect(route('admin.pegawai.index'));

        $this->assertSame('Nama Baru', $pegawai->fresh()->nama);
    }

    #[Test]
    public function update_boleh_pakai_nip_sendiri(): void
    {
        $pegawai = Pegawai::factory()->create();

        $this->actingAs($this->verifiedUser())
            ->put(route('admin.pegawai.update', $pegawai), array_merge(
                Pegawai::factory()->make()->toArray(),
                ['nip' => $pegawai->nip, 'nama' => $pegawai->nama]
            ));

        $this->assertNull(session('errors'));
    }

    #[Test]
    public function destroy_soft_delete_lalu_restore(): void
    {
        $pegawai = Pegawai::factory()->create();

        $this->actingAs($this->verifiedUser())
            ->delete(route('admin.pegawai.destroy', $pegawai))
            ->assertRedirect(route('admin.pegawai.index'));

        $this->assertSoftDeleted($pegawai);

        $this->actingAs($this->verifiedUser())
            ->post(route('admin.pegawai.restore', $pegawai->id))
            ->assertRedirect(route('admin.pegawai.index'));

        $this->assertDatabaseHas('pegawai', ['id' => $pegawai->id, 'deleted_at' => null]);
    }
}
