<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('articles', function(Blueprint $table) {
			$table->foreign('feed_id')->references('id')->on('feeds')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('article_tags', function(Blueprint $table) {
			$table->foreign('article_id')->references('id')->on('articles')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('article_tags', function(Blueprint $table) {
			$table->foreign('tag_id')->references('id')->on('tags')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('feed_tags', function(Blueprint $table) {
			$table->foreign('feed_id')->references('id')->on('feeds')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('feed_tags', function(Blueprint $table) {
			$table->foreign('tag_id')->references('id')->on('tags')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tag_cmp', function(Blueprint $table) {
			$table->foreign('tag_id1')->references('id')->on('tags')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tag_cmp', function(Blueprint $table) {
			$table->foreign('tag_id2')->references('id')->on('tags')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('user_tags', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('user_tags', function(Blueprint $table) {
			$table->foreign('tag_id')->references('id')->on('tags')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('articles', function(Blueprint $table) {
			$table->dropForeign('articles_feed_id_foreign');
		});
		Schema::table('article_tags', function(Blueprint $table) {
			$table->dropForeign('article_tags_article_id_foreign');
		});
		Schema::table('article_tags', function(Blueprint $table) {
			$table->dropForeign('article_tags_tag_id_foreign');
		});
		Schema::table('feed_tags', function(Blueprint $table) {
			$table->dropForeign('feed_tags_feed_id_foreign');
		});
		Schema::table('feed_tags', function(Blueprint $table) {
			$table->dropForeign('feed_tags_tag_id_foreign');
		});
		Schema::table('tag_cmp', function(Blueprint $table) {
			$table->dropForeign('tag_cmp_tag_id1_foreign');
		});
		Schema::table('tag_cmp', function(Blueprint $table) {
			$table->dropForeign('tag_cmp_tag_id2_foreign');
		});
		Schema::table('user_tags', function(Blueprint $table) {
			$table->dropForeign('user_tags_user_id_foreign');
		});
		Schema::table('user_tags', function(Blueprint $table) {
			$table->dropForeign('user_tags_tag_id_foreign');
		});
	}
}