<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_lists', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('key');
            $table->string('description')->nullable();

            $table->integer('plugin_id')->unsigned();
            $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade');

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
        Schema::dropIfExists('field_lists');
    }
}
