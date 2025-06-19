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
        Schema::create('detail_gajis', function (Blueprint $table) {
            $table->id();
            $table->enum('status_gaji', ['harian', 'bulanan']);
            $table->integer('gaji_bulanan')->nullable()->default(0);
            $table->integer('gaji_harian')->nullable()->default(0);
            $table->integer('uang_makan')->nullable()->default(0);
            $table->integer('bonus')->nullable()->default(0);
            $table->integer('tunjangan')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_gajis');
    }
};
