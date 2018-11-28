<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallationPluginTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('installation_plugin', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('priority')->nullable();

			$table->integer('installation_id')->unsigned();
			$table->foreign('installation_id')->references('id')->on('installations')->onDelete('cascade');

			$table->integer('plugin_id')->unsigned();
			$table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade');

			$table->integer('config_id')->unsigned()->nullable();
			$table->foreign('config_id')->references('id')->on('configs')->onDelete('set null');

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
		Schema::dropIfExists('installation_plugin');
	}
}
