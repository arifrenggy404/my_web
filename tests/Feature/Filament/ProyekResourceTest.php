<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use App\Models\Proyek;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProyekResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_dari_dasbor_admin()
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_tamu_diarahkan_dari_indeks_proyek()
    {
        $response = $this->get('/admin/proyeks');

        $response->assertRedirect('/admin/login');
    }

    public function test_pengguna_terautentikasi_dapat_mengakses_dasbor_admin()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertSuccessful();
    }

    public function test_pengguna_terautentikasi_dapat_mengakses_indeks_proyek()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/proyeks');

        $response->assertSuccessful();
    }
}
