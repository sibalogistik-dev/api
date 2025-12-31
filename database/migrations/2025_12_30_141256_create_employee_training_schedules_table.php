<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_training_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_training_id');
            $table->unsignedBigInteger('mentor_id')->nullable();
            $table->dateTime('schedule_time');
            $table->string('title');
            $table->longText('activity_description');
            $table->longText('activity_result')->nullable();
            $table->longText('mentor_notes')->nullable();
            $table->integer('mentor_assessment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_training_schedules');
    }
};
