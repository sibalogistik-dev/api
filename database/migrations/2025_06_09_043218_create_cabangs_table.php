<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('cabangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->comment('Nama Cabang');
            $table->string('alamat')->comment('Alamat Cabang');
            $table->string('telepon')->nullable()->comment('Nomor Telepon Cabang');
            $table->integer('kota_id')->comment('ID Kota');
            $table->integer('perusahaan_id')->comment('ID Perusahaan');
            $table->decimal('latitude', 10, 8)->nullable()->comment('Latitude Lokasi Cabang');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Longitude Lokasi Cabang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('cabangs');
    }
};
