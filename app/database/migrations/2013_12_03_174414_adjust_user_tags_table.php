<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AdjustUserTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_tags', function($table)
                    {
                        $table->integer('footprint')->unsigned();
                     });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_tags', function($table)
                    {
                        $table->dropColumn('footprint');
                    });
	}

}