<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class TickTable extends Migration {

	public function up()
	{
		Schema::create('tick', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->boolean('active')->default('true');
			$table->integer('articles_retrieved')->unsigned();
			$table->integer('feeds_checked')->unsigned();
			$table->float('duration')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('tick');
	}

}