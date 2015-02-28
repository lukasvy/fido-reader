<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration {

	public function up()
	{
		Schema::create('articles', function(Blueprint $table) {
			$table->increments('id')->index();
			$table->timestamps();
			$table->boolean('active')->default('true');
			$table->integer('feed_id')->unsigned()->index();
			$table->string('url');
			$table->string('media')->default('');
			$table->string('title')->index();
		});
	}

	public function down()
	{
		Schema::drop('articles');
	}
}