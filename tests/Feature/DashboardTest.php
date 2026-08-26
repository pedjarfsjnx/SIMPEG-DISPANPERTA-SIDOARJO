<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_dashboard_hanya_untuk_user_terverifikasi(): void
    {
        $belumVerifikasi = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($belumVerifikasi)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('verification.notice'));

        $terverifikasi = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($terverifikasi)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    #[Test]
    public function tamu_tidak_bisa_akses_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect('/login');
    }
}
