<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
			$table->increments('id');

			$table->string('name');

			$table->integer('priority')->nullable();

			$table->string('slug')->unique();

			$table->unsignedInteger('list_id');
			$table->foreign('list_id')->references('id')->on('lists');

			$table->integer('owner_id')->unsigned();
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
        Schema::table('configs', function (Blueprint $table) {
            //
        });
    }
}
