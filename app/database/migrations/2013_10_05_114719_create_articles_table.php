<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration {

	public function up()
	{
		Schema::create('articles', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->boolean('active')->default('true');
			$table->integer('feed_id')->unsigned();
			$table->string('url');
			$table->string('title');
		});
	}

	public function down()
	{
		Schema::drop('articles');
	}
}