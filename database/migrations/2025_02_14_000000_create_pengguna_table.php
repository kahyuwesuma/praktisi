<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('token', 20)->comment('NIM untuk mahasiswa dan NIP untuk dosen');
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('peran_pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->constrained('pengguna')->onDelete('cascade');
            $table->enum('peran', ['DOSEN', 'ASISTEN_LAB', 'PRAKTIKAN']);
            $table->timestamps();
        });

        Schema::create('tahun_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 15);
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_ruangan');
            $table->text('link_spreadsheet_nilai_akhir')->nullable();
            $table->foreignId('id_dosen')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->foreignId('id_tahun_akademik')->constrained('tahun_akademik')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('tahun_akademik');
        Schema::dropIfExists('peran_pengguna');
        Schema::dropIfExists('pengguna');
    }
};
