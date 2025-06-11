<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->comment('Nama karyawan');
            $table->string('nik')->unique()->comment('Nomor Induk Karyawan');
            $table->enum('jabatan', ['Administrator', 'Direksi', 'Manajerial', 'Staff'])->comment('Jabatan karyawan');
            $table->string('divisi_id')->nullable()->comment('Alamat karyawan');
            $table->string('no_telepon')->nullable()->comment('Nomor telepon karyawan');
            $table->string('alamat')->nullable()->comment('Alamat karyawan');
            $table->integer('cabang_id')->unsigned()->comment('ID cabang tempat karyawan bekerja');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('karyawans');
    }
};
