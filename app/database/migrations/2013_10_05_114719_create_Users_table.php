<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('email')->index();
			$table->string('password');
			$table->boolean('active')->default('true');
			$table->string('username')->unique();
			$table->string('role')->default('user');
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}