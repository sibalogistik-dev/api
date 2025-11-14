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
            $table->string('check_in_image')->nullable()->default('uploads/check_in_image/default.webp');
            $table->string('check_out_image')->nullable()->default(null);
            $table->string('description')->nullable();
            $table->float('check_in_longitude', 10)->nullable()->default('0.00000000');
            $table->float('check_in_latitude', 10)->nullable()->default('0.00000000');
            $table->float('check_out_longitude', 10)->nullable()->default('0.00000000');
            $table->float('check_out_latitude', 10)->nullable()->default('0.00000000');
            $table->boolean('half_day')->nullable()->default(false);
            $table->string('sick_note')->nullable()->default('uploads/sick_note/default.webp');
            $table->integer('late_arrival_time')->nullable()->default(0);
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
