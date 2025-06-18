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
            $table->integer('user_id')->unsigned()->comment('ID user login');
            $table->integer('cabang_id')->unsigned()->comment('ID cabang tempat karyawan bekerja');
            $table->integer('jabatan_id')->comment('ID Jabatan karyawan');
            $table->string('nik')->unique()->comment('Nomor Induk Karyawan');
            $table->string('no_telepon')->nullable()->comment('Nomor telepon karyawan');
            $table->string('alamat')->nullable()->comment('Alamat karyawan');
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
