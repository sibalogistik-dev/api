<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    public function up()
    {
        Schema::create(config('laravolt.indonesia.table_prefix') . 'cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->char('code')->unique();
            // $table->char('province_code');
            $table->bigInteger('code')->unique();
            $table->bigInteger('province_code');
            $table->string('name', 255);
            $table->text('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop(config('laravolt.indonesia.table_prefix') . 'cities');
    }
}
