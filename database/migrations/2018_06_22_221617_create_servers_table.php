<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *,
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');

			$table->string('name');
			$table->string('ip');
			$table->string('port');
			$table->string('password');

			$table->string('ftp_host');
			$table->string('ftp_user');
			$table->string('ftp_password');
			$table->string('ftp_root');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			$table->dateTime('render_requested_at')->nullable();
			$table->dateTime('rendered_at')->nullable();

			$table->dateTime('sync_requested_at')->nullable();
			$table->dateTime('synced_at')->nullable();

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
        Schema::dropIfExists('servers');
    }
}
