<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNotificacionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notificaciones', function(Blueprint $table)
		{
			$table->foreign('id_bauche', 'FK_notificaciones_bauches')->references('id')->on('bauches')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notificaciones', function(Blueprint $table)
		{
			$table->dropForeign('FK_notificaciones_bauches');
		});
	}

}
