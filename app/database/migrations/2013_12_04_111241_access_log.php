<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AccessLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('access_log', function(Blueprint $table) {
                        $table->increments('id');
                        $table->timestamps();
                        $table->integer('user_id')->unsigned();
                        $table->integer('type')->unsigned();
			$table->string('ip')->unsigned();
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
		Schema::drop('access_log');
	}

}