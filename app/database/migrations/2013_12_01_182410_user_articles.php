<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_articles', function(Blueprint $table) {
			$table->increments('id')->index();
			$table->timestamps();
			$table->integer('user_id')->unsigned()->index();
			$table->integer('article_id')->unsigned()->index();
			$table->boolean('active')->default('true');
			$table->foreign('article_id')->references('id')->on('articles')
						->onDelete('restrict')
						->onUpdate('restrict');
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_articles');
	}

}