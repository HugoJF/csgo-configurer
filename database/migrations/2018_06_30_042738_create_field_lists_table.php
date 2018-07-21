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

            $table->unsignedInteger('owner_id');
            $table->string('owner_type');

            $table->unsignedInteger('file_id')->nullable();
            $table->foreign('file_id')->references('id')->on('files')->onDelete('set null');

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
