<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDetalleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('detalle', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('id_producto')->index('FK_detalle_productos');
			$table->integer('id_factura')->index('FK_detalle_factura');
			$table->float('precio', 10, 0)->nullable();
			$table->string('descripcion', 200)->nullable();
			$table->integer('cantidad')->nullable();
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
		Schema::drop('detalle');
	}

}
