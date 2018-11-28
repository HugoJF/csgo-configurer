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

			$table->unsignedInteger('field_list_id');
			$table->foreign('field_list_id')->references('id')->on('field_lists');

			$table->unsignedInteger('file_id')->nullable();
			$table->foreign('file_id')->references('id')->on('files')->onDelete('set null');

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
