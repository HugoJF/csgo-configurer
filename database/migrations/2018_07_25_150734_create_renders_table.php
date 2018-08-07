<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renders', function (Blueprint $table) {
            $table->increments('id');

            $table->json('logs');
            $table->unsignedInteger('duration');

            $table->unsignedInteger('server_id');
            $table->foreign('server_id')->references('id')->on('servers');

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
        Schema::dropIfExists('renders');
    }
}
