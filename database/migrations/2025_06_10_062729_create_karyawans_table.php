<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->string('npk');
            $table->integer('job_title_id');
            $table->integer('manager_id')->nullable();
            $table->integer('branch_id');
            $table->date('start_date');
            $table->date('end_date')->nullable()->default(null);
            $table->string('contract')->nullable()->default(null);
            $table->string('bank_account_number')->nullable()->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
