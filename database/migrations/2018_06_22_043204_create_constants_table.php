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

			$table->string('key');
			$table->string('value');

			$table->integer('config_id')->unsigned();
			$table->foreign('config_id')->references('id')->on('configs')->onDelete('cascade');

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
