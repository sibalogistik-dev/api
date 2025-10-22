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
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('attendance_image')->nullable()->default('uploads/attendance_image/default.webp');
            $table->string('description')->nullable();
            $table->float('longitude', 10)->nullable()->default('0.00000000');
            $table->float('latitude', 10)->nullable()->default('0.00000000');
            $table->timestamps();
            $table->softDeletes();
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
