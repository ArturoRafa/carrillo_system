<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBauchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bauches', function(Blueprint $table)
		{
			$table->foreign('cedula_usuario', 'FK_bauches_usuarios')->references('cedula')->on('usuarios')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bauches', function(Blueprint $table)
		{
			$table->dropForeign('FK_bauches_usuarios');
		});
	}

}
