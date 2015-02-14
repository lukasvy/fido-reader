<?php

use Illuminate\Database\Migrations\Migration;

class UpdateFeedsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('feeds', function($table)
                    {
                        $table->string('name')->default('');
                     });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_tags', function($table)
                    {
                        $table->dropColumn('name');
                    });
	}

}