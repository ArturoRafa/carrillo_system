<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFacturaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('factura', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('num_factura', 45)->nullable();
			$table->date('fecha_facturacion')->nullable();
			$table->string('cedula_usuario', 200)->nullable()->index('FK_factura_usuarios');
			$table->string('nombre', 200)->nullable();
			$table->string('email', 200)->nullable();
			$table->float('total', 10, 0)->nullable();
			$table->string('telefono', 200)->nullable();
			$table->integer('estado')->nullable()->default(1);
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
		Schema::drop('factura');
	}

}
