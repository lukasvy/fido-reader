<?php

use Illuminate\Database\Migrations\Migration;

class UpdateArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('articles', function($table)
		    {
		        $table->string('desc', 1000)->nullable();
		        $table->string('permalink', 1000)->nullable();
		        $table->string('author', 1000)->nullable();
		        $table->string('category', 500)->nullable();
		        $table->string('author_email', 100)->nullable();
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
		        $table->dropColumn('desc');
		        $table->dropColumn('permalink');
		        $table->dropColumn('author');
		        $table->dropColumn('category');
		        $table->dropColumn('author_email');
		    });
	}

}