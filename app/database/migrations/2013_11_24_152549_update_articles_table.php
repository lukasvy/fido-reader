<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
		        $table->string('desc')->nullable();
		        $table->string('permalink')->nullable();
		        $table->string('author')->nullable();
		        $table->string('category')->nullable();
		        $table->string('author_email')->nullable();
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