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
    public function admin_dashboard_bisa_diakses_oleh_user_terautentikasi(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    #[Test]
    public function tamu_tidak_bisa_akses_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect('/login');
    }
}
