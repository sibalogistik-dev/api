<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_karyawans', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->string('nik');
            $table->integer('jabatan_id');
            $table->integer('cabang_id');
            $table->integer('daerah_tinggal_id');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_karyawans');
    }
};
