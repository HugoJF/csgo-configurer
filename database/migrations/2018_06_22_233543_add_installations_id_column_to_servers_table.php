<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstallationsIdColumnToServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
			$table->integer('installation_id')->unsigned()->nullable();
			$table->foreign('installation_id')->references('id')->on('installations')->onDelete('set null');

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
        	$table->dropForeign('installation_id');
        	$table->dropColumn('installation_id');
        });
    }
}
