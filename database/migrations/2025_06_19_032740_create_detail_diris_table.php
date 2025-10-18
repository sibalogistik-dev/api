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
        Schema::create('detail_diris', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->integer('religion_id');
            $table->string('phone_number');
            $table->integer('place_of_birth_id');
            $table->date('date_of_birth');
            $table->string('address');
            $table->enum('blood_type', ['a', 'b', 'ab', 'o', 'none'])->default('none');
            $table->integer('education_id');
            $table->integer('marriage_status_id');
            $table->integer('residential_area_id');
            $table->string('passport_photo')->default('uploads/pas_foto/default.webp');
            $table->string('id_card_photo')->default('uploads/ktp_foto/default.webp');
            $table->string('drivers_license_photo')->default('uploads/sim_foto/default.webp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_diris');
    }
};
