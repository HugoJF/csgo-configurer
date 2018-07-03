<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fields', function (Blueprint $table) {
			$table->increments('id');

			$table->string('name');
			$table->string('description');
			$table->string('key');
			$table->string('default');

			$table->unsignedInteger('field_list_id');
			$table->foreign('field_list_id')->references('id')->on('field_lists');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('fields');
	}
}
