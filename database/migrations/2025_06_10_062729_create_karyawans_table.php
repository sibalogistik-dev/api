<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->comment('Nama karyawan');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->comment('Jenis Kelamin karyawan');
            $table->integer('agama_id')->comment('Agama karyawan');
            $table->integer('tempat_lahir_id')->comment('Tempat Lahir karyawan');
            $table->enum('golongan_darah', ['a', 'b', 'ab', 'o', 'none'])->comment('Golongan Darah karyawan');
            $table->integer('pendidikan_id')->comment('Pendidikan karyawan');
            $table->string('alamat')->comment('Alamat karyawan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
