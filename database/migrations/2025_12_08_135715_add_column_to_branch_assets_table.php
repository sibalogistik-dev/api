<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('branch_assets', function (Blueprint $table) {
            $table->boolean('is_vehicle')->default(false)->after('asset_type_id')->change();
            $table->bigInteger('price', 0, 1)->nullable()->default(0)->change();
            $table->string('image_path')->nullable()->default(null)->after('description');
            $table->integer('quantity')->default(1)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('branch_assets', function (Blueprint $table) {
            //
        });
    }
};
