<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('midday_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->dateTime('date_time');
            $table->float('longitude', 10)->nullable()->default('0.00000000');
            $table->float('latitude', 10)->nullable()->default('0.00000000');
            $table->string('desctiption');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('midday_attendances');
    }
};
