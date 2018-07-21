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

			$table->boolean('required');

			$table->string('name');
			$table->text('description');
			$table->string('key');
			$table->string('default')->nullable();

			$table->unsignedInteger('owner_id');
			$table->string('owner_type');

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
