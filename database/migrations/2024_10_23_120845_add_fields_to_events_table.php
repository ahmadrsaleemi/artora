<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('headline')->default(NULL);
            $table->string('endDate')->default(NULL);
            $table->string('venue_name')->default(NULL);
            $table->string('venue_address')->default(NULL);
            $table->string('event_organizer_Description')->default(NULL);
            $table->string('event_organizer_phone')->default(NULL);
            $table->string('event_organizer_email')->default(NULL);
            $table->string('event_organizer_website')->default(NULL);
            $table->string('event_organizer_social_media')->default(NULL);
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
            $table->dropColumn('headline');
            $table->dropColumn('endDate');
            $table->dropColumn('venue_name');
            $table->dropColumn('venue_address');
            $table->dropColumn('event_organizer_Description');
            $table->dropColumn('event_organizer_phone');
            $table->dropColumn('event_organizer_email');
            $table->dropColumn('event_organizer_website');
            $table->dropColumn('event_organizer_social_media');
        });
    }
}
