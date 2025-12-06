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
        Schema::table('perusahaans', function (Blueprint $table) {
            $table->string('email')->nullable()->after('codename');
            $table->string('website')->nullable()->after('email');
            $table->string('company_brand')->nullable()->after('website');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company', function (Blueprint $table) {
            //
        });
    }
};
