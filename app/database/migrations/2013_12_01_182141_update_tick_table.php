<?php

use Illuminate\Database\Migrations\Migration;

class UpdateTickTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tick', function($table)
		    {
		        $table->integer('tags_created')->unsigned();
		        $table->integer('tags_matched')->unsigned();
		     });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('articles', function($table)
		    {
		        $table->dropColumn('tags_created');
		        $table->dropColumn('tags_matched');
		    });
	}

}