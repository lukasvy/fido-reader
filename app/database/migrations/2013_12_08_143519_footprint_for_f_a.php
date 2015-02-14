<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class FootprintForFA extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('articles', function($table)
                    {
                        $table->integer('footprint')->unsigned()->default(0);
                     });
        Schema::table('feeds', function($table)
                    {
                        $table->integer('footprint')->unsigned()->default(0);
                     });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('articles', function($table)
                    {
                        $table->dropColumn('footprint');
                    });
        Schema::table('feeds', function($table)
                    {
                        $table->dropColumn('footprint');
                    });
	}

}