<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticleTagsTable extends Migration {

	public function up()
	{
		Schema::create('article_tags', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('article_id')->unsigned();
			$table->integer('tag_id')->unsigned();
			$table->boolean('active')->default('true');
		});
	}

	public function down()
	{
		Schema::drop('article_tags');
	}
}