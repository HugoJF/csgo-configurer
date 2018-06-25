<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallationTemplateTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('installation_template', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('installation_id')->unsigned();
			$table->foreign('installation_id')->references('id')->on('installations')->onDelete('cascade');

			$table->integer('template_id')->unsigned();
			$table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');

			$table->integer('bundle_id')->unsigned()->nullable();
			$table->foreign('bundle_id')->references('id')->on('bundles')->onDelete('set null');

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
		Schema::dropIfExists('installation_template');
	}
}
