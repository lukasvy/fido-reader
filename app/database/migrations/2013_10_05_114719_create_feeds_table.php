<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedsTable extends Migration {

	public function up()
	{
		Schema::create('feeds', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->boolean('active')->default('true');
			$table->string('url');
		});
	}

	public function down()
	{
		Schema::drop('feeds');
	}
}