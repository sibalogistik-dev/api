<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('creator_id');
            $table->date('maintenance_date');
            $table->unsignedBigInteger('min_maintenance_cost');
            $table->unsignedBigInteger('max_maintenance_cost')->nullable();
            $table->longText('description');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('actual_maintenance_cost')->nullable();
            $table->string('receipt')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
