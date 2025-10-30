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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('period_name');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('salary_type', ['monthly', 'daily'])->default('monthly');
            $table->integer('base_salary')->default(0);
            $table->integer('days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('half_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('sick_days')->default(0);
            $table->integer('leave_days')->default(0);
            $table->integer('permission_days')->default(0);
            $table->integer('off_days')->default(0);
            $table->integer('late_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->integer('deductions')->default(0);
            $table->integer('allowances')->default(0);
            $table->integer('overtime')->default(0);
            $table->integer('net_salary')->default(0);
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
