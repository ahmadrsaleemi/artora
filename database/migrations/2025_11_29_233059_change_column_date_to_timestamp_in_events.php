<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnDateToTimestampInEvents extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function (Blueprint $table) {
			$table->dropColumn('date');
			$table->dropColumn('eventDate');
			$table->dropColumn('endDate');
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
			$table->string('date')->change();
			$table->string('eventDate')->change();
			$table->string('endDate')->change();
		});
	}
}
