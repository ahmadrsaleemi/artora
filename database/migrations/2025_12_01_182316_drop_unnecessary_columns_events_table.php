<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnnecessaryColumnsEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('contactNumber');
            $table->dropColumn('contactEmail');
            $table->dropColumn('eventOrganizerId');
            $table->dropColumn('venue_name');
            $table->dropColumn('venue_address');
            $table->dropColumn('event_organizer_Description');
            $table->dropColumn('event_organizer_phone');
            $table->dropColumn('event_organizer_email');
            $table->dropColumn('event_organizer_website');
            $table->dropColumn('event_organizer_social_media');
            $table->dropColumn('eventDate');
            $table->dropColumn('endDate');
            $table->string('subtitle')->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('category')->nullable();
            $table->integer('ticketprice')->nullable();      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('contactNumber')->nullable();
            $table->string('contactEmail')->nullable();
            $table->integer('eventOrganizerId')->nullable();
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
            $table->string('event_organizer_Description')->nullable();
            $table->integer('event_organizer_phone')->nullable();
            $table->string('event_organizer_email')->nullable();
            $table->string('event_organizer_website')->nullable();
            $table->string('event_organizer_social_media')->nullable();
            $table->timestamp('eventDate')->nullable();
            $table->timestamp('endDate')->nullable();
            $table->dropColumn('subtitle');
            $table->dropColumn('capacity');
            $table->dropColumn('category');
            $table->dropColumn('ticketprice');   
        });
    }
}
