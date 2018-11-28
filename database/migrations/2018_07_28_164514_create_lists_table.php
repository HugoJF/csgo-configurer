<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lists', function (Blueprint $table) {
			$table->increments('id');

			$table->string('name');
			$table->string('description')->nullable();

			$table->boolean('active')->default(true);

			$table->string('key')->nullable();
			$table->boolean('overwrites')->default(false);

			$table->unsignedInteger('field_list_id')->nullable();
			$table->foreign('field_list_id')->references('id')->on('field_lists')->onDelete('set null');

			$table->nestedSet();

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
		Schema::dropIfExists('lists');
	}
}
