<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reservation_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->integer('price');
            $table->boolean('status');
            $table->string('payment_method');
            $table->time('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
