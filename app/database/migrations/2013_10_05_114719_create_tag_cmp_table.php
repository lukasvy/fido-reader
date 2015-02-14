<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagCmpTable extends Migration {

	public function up()
	{
		Schema::create('tag_cmp', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('tag_id1')->unsigned();
			$table->integer('tag_id2')->unsigned();
			$table->integer('cmp')->default(0);
			$table->boolean('active')->default('true');
		});
	}

	public function down()
	{
		Schema::drop('tag_cmp');
	}
}