<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDetalleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('detalle', function(Blueprint $table)
		{
			$table->foreign('id_factura', 'FK_detalle_factura')->references('id')->on('factura')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('id_producto', 'FK_detalle_productos')->references('id')->on('productos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('detalle', function(Blueprint $table)
		{
			$table->dropForeign('FK_detalle_factura');
			$table->dropForeign('FK_detalle_productos');
		});
	}

}
