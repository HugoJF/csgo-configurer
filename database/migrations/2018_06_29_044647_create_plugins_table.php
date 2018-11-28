<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug')->unique();

            $table->string('name');
            $table->string('description');

            $table->string('folder');

            $table->unsignedInteger('field_list_id');
            $table->foreign('field_list_id')->references('id')->on('field_lists');

            $table->timestamp('modified_at');

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
        Schema::dropIfExists('plugins');
    }
}
