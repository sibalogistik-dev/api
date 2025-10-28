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
            $table->integer('employee_id');
            $table->enum('salary_type', ['monthly', 'daily'])->nullable()->default('daily');
            $table->integer('monthly_base_salary')->nullable()->default(0);
            $table->integer('daily_base_salary')->nullable()->default(0);
            $table->integer('meal_allowance')->nullable()->default(0);
            $table->integer('bonus')->nullable()->default(0);
            $table->integer('allowance')->nullable()->default(0);
            $table->integer('overtime')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
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
