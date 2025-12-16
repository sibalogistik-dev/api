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
        Schema::table('warning_letters', function (Blueprint $table) {
            $table->enum('letter_number', ['1', '2', '3'])->default('1')->after('issued_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warning_letter', function (Blueprint $table) {
            //
        });
    }
};
