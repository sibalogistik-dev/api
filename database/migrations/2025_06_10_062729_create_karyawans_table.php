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
            $table->integer('user_id');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->integer('agama_id');
            $table->string('no_telp');
            $table->integer('tempat_lahir_id');
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->enum('golongan_darah', ['a', 'b', 'ab', 'o', 'none'])->default('none');
            $table->integer('pendidikan_id');
            $table->enum('status_kawin', ['belum kawin', 'kawin', 'janda', 'duda']);
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
