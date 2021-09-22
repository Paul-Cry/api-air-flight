<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('flight_code');
            $table->unsignedBigInteger('from_id');
            $table->foreign('from_id')->references('id')->on('airports');

            $table->unsignedBigInteger('to_id');
            $table->foreign('to_id')->references('id')->on('airports');
            // $table->foreign('to_id')->references('id')->on('airports');

            $table->date('date');
            $table->string('time_to');
            $table->string('time_from');
            $table->integer('cost');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}
