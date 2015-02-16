<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedTagsTable extends Migration {

	public function up()
	{
		Schema::create('feed_tags', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('feed_id')->unsigned()->index();
			$table->integer('tag_id')->unsigned()->index();
			$table->boolean('active')->default('true');
		});
	}

	public function down()
	{
		Schema::drop('feed_tags');
	}
}