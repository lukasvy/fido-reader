<?php

use Illuminate\Database\Migrations\Migration;

class AddNameToFeeds extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		    {
		        $table->string('first_name', 100)->nullable();
		        $table->string('last_name', 100)->nullable();
		    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table)
		    {
		        $table->dropColumn('first_name');
		        $table->dropColumn('last_name');
		    });
	}

}