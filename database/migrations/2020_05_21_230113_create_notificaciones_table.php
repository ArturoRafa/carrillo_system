<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificacionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notificaciones', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->smallInteger('tipo')->nullable()->default(1);
			$table->integer('id_bauche')->index('FK_notificaciones_bauches');
			$table->string('estado', 200)->nullable();
			$table->string('cedula', 200);
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
		Schema::drop('notificaciones');
	}

}
