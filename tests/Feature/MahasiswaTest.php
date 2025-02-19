<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MahasiswaTest extends TestCase {
    use RefreshDatabase;

    public function test_create_mahasiswa(): void {
        $response = $this->post('/admin/mahasiswa/create', [
            'nama' => 'John Doe',
            'nim' => '22001234',
            'email' => 'johndoe@example.com',
            'jurusan' => 'Informatika',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mahasiswa', ['nim' => '22001234']);
    }

    public function test_read_mahasiswa(): void {
        $mahasiswa = Mahasiswa::factory()->create();
        $response = $this->get('/admin/mahasiswa');
        $response->assertStatus(200);
        $response->assertSee($mahasiswa->nama);
    }

    public function test_update_mahasiswa(): void {
        $mahasiswa = Mahasiswa::factory()->create();
        $response = $this->put("/admin/mahasiswa/{$mahasiswa->id}/edit", [
            'nama' => 'Jane Doe',
            'nim' => '22009999',
            'email' => 'janedoe@example.com',
            'jurusan' => 'Sistem Informasi',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mahasiswa', ['nim' => '22009999']);
    }
