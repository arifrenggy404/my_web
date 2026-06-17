<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KontrolerProyekTest extends TestCase
{
    use RefreshDatabase;

    public function test_ambil_semua_proyek_berhasil(): void
    {
        $this->seed(\Database\Seeders\SeederProyek::class);

        $response = $this->getJson('/api/proyek');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'sukses',
                     'data' => [
                         '*' => [
                             'id',
                             'nama_proyek',
                             'tautan_slug',
                             'deskripsi',
                             'teknologi_utama',
                             'tautan_langsung',
                             'tautan_github',
                             'jalur_gambar'
                         ]
                     ]
                 ])
                 ->assertJsonFragment([
                     'nama_proyek' => 'Sistem Informasi Keamanan Neo-Tokyo',
                     'tautan_slug' => 'sistem-informasi-keamanan-neo-tokyo'
                 ]);
    }
}
