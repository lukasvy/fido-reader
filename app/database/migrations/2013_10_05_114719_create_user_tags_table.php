<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTagsTable extends Migration {

	public function up()
	{
		Schema::create('user_tags', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->unsigned()->index();
			$table->integer('tag_id')->unsigned()->index();
			$table->boolean('active')->default('true');
		});
	}

	public function down()
	{
		Schema::drop('user_tags');
	}
}