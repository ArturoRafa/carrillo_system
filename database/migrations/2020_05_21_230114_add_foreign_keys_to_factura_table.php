<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFacturaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('factura', function(Blueprint $table)
		{
			$table->foreign('cedula_usuario', 'FK_factura_usuarios')->references('cedula')->on('usuarios')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('factura', function(Blueprint $table)
		{
			$table->dropForeign('FK_factura_usuarios');
		});
	}

}
