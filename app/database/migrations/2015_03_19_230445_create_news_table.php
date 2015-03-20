<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function($table){
			$table->increments('id');
			$table->string('title');
			$table->string('url');
			$table->string('category');
			$table->string('tags');
			$table->dateTime('timestamp');
			$table->string('curator');
			$table->string('source');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('news');
	}

}
