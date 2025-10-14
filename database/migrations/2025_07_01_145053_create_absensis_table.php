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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('attendance_status_id');
            $table->date('date')->default(now());
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('attendance_image')->nullable()->default('storage/uploads/attendance_image/default.webp');
            $table->string('description')->nullable();
            $table->string('longitude')->nullable()->default('0.00000000');
            $table->string('latitude')->nullable()->default('0.00000000');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
