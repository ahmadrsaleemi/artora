<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('leader_phone')->default(NULL);
            $table->string('guest_phone')->default(NULL);
            $table->string('guest_email')->default(NULL);
            $table->string('amount')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('leader_phone');
            $table->dropColumn('guest_phone');
            $table->dropColumn('guest_email');
            $table->dropColumn('amount');
        });
    }
}
