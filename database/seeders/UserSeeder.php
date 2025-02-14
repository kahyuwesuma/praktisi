<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon; // Untuk menangani waktu

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan data pengguna dengan hash password
        User::create([
            'name' => 'Praktisi', // Menambahkan kolom name
            'email' => 'praktisiunmul@gmail.com',
            'password' => Hash::make('Praktisi2025'), // Password di-hash menggunakan Bcrypt
            'created_at' => Carbon::now(), // Menetapkan waktu sekarang untuk created_at
            'updated_at' => Carbon::now(), // Menetapkan waktu sekarang untuk updated_at
        ]);
    }
}
