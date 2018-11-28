<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstantsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('constants', function (Blueprint $table) {
			$table->increments('id');

			$table->boolean('active');

			$table->string('key');
			$table->text('value');

			$table->unsignedInteger('list_id');
			$table->foreign('list_id')->references('id')->on('lists');

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
		Schema::dropIfExists('constant');
	}
}
