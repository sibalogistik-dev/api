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
        Schema::create('job_descriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_title_id')->unsigned();
            $table->string('task_name')->nullable();
            $table->text('task_detail')->nullable();
            $table->tinyInteger('priority_level')->default(1)->comment('1=Very Low, 2=Low, 3=Medium, 4=High, 5=Very High');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_descriptions');
    }
};
