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
            $table->integer('monthly_base_salary')->default(0);
            $table->integer('total_present_days')->default(0);
            $table->integer('total_absent_days')->default(0);
            $table->integer('total_sick_days')->default(0);
            $table->integer('total_leave_days')->default(0);
            $table->integer('total_permission_days')->default(0);
            $table->integer('total_off_days')->default(0);
            $table->integer('total_late_minutes')->default(0);
            $table->integer('overtime_hours')->default(0);
            $table->integer('deductions')->default(0);
            $table->integer('allowances')->default(0);
            $table->integer('net_salary')->default(0);
            $table->timestamp('generated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
