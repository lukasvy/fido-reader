<?php

use Illuminate\Database\Migrations\Migration;

class UpdateArticlesTable2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('articles', function($table)
		    {
		        $table->string('article_date', 1000)->nullable();
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
		        $table->dropColumn('article_date');
		    });
	}

}