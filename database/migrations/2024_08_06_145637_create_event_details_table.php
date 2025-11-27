<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_details', function (Blueprint $table) {
            $table->increments('edid');
            $table->integer('numberOfTickets');
            $table->integer('eventAccess');
            $table->integer('ticketPrice');
            $table->string('eventType');
            $table->string('seatArrangement');
            $table->integer('ticketDivision');
            $table->integer('eid');
     
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
        Schema::dropIfExists('event_details');
    }
}
