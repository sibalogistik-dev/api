<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id');
            $table->foreignId('asset_type_id');
            $table->boolean('is_vehicle');
            $table->string('name');
            $table->bigInteger('price', 0, 1)->nullable()->default(0);
            $table->date('purchase_date');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_assets');
    }
};
