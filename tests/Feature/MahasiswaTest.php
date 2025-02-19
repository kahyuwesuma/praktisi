<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MahasiswaTest extends TestCase {
    use RefreshDatabase;

    public function test_read_mahasiswa(): void {
        $mahasiswa = Mahasiswa::factory()->create();

        $response = $this->get('/praktisi/mahasiswas');
        echo "";

        $response->assertStatus(200);
        $response->assertSee($mahasiswa->nama);
    }
}
