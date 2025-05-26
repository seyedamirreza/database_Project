<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('class_air_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('class_air_id')->references('id')->on('class_airs');
            $table->string('airline_name');
            $table->string('flight_number');
            $table->string('source_airport');
            $table->string('destination_airport');
            $table->integer('stops');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
};
