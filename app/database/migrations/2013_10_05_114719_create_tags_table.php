<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsTable extends Migration {

	public function up()
	{
		Schema::create('tags', function(Blueprint $table) {
			$table->increments('id')->index();
			$table->timestamps();
			$table->string('tag')->index();
			$table->boolean('active')->default('true');
		});
	}

	public function down()
	{
		Schema::drop('tags');
	}
}