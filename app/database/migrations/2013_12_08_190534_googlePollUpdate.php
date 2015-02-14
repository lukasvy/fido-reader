<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class GooglePollUpdate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('google_poll', function($table)
                    {
                        $table->float('duration')->unsigned();
                     });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('google_poll', function($table)
		    {
		        $table->dropColumn('duration');
		    });
	}

}