<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class GooglePollTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('google_poll', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('tag_id')->unsigned();
			$table->integer('feed_id')->unsigned();
			$table->boolean('active')->default('true');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('google_poll');
	}

}