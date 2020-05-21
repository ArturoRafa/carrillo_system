<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('productos', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('codigo_barras', 200)->nullable();
			$table->smallInteger('estado')->nullable();
			$table->float('precio_venta', 10, 0)->nullable();
			$table->float('precio_compra', 10, 0)->nullable();
			$table->string('marca', 200)->nullable();
			$table->string('modelo', 200)->nullable();
			$table->string('color', 200)->nullable();
			$table->string('garantia', 100)->nullable();
			$table->string('descripcion', 200)->nullable();
			$table->string('imagen', 200)->nullable();
			$table->integer('tipo')->nullable();
			$table->integer('cantidad_disponible')->nullable();
			$table->timestamps();
			$table->smallInteger('tipo_producto')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('productos');
	}

}
