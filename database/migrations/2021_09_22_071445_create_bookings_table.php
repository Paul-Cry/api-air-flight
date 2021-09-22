<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *         'flight_from',
    'date_from',
    'flight_back',
    'date_back',
    'code'
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('flight_from');
//            $table->unsignedBigInteger('flight_from');
//            $table->foreign('from_id')->references('id')->on('airports');

            $table->string('date_from');
            $table->string('date_back');

            $table->string('flight_back');
//            $table->unsignedBigInteger('flight_back');
//            $table->foreign('from_id')->references('id')->on('airports');

            $table->string('code');


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
        Schema::dropIfExists('bookings');
    }
}
