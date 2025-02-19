<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory {
    protected $model = Mahasiswa::class;

    public function definition(): array {
        return [
            'nama' => $this->faker->name,
            'nim' => $this->faker->unique()->numerify('2200####'),
            'email' => $this->faker->unique()->safeEmail,
            'jurusan' => $this->faker->randomElement(['Informatika', 'Sistem Informasi', 'Teknik Komputer']),
        ];
    }
}
