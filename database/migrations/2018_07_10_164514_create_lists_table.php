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

			$table->boolean('active');

			$table->string('key')->nullable();
			$table->boolean('overwrites')->default(false);

			$table->unsignedInteger('field_list_id')->nullable();
			$table->foreign('field_list_id')->references('id')->on('field_lists')->onDelete('set null');

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
		Schema::dropIfExists('lists');
	}
}
