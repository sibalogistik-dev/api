<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('job_description_id');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_daily_reports');
    }
};
