<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBauchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bauches', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->date('fecha_ingreso')->nullable();
			$table->string('descripcion', 200)->nullable();
			$table->date('fecha_salida')->nullable();
			$table->string('cedula_usuario', 200)->nullable()->index('FK_bauches_usuarios');
			$table->smallInteger('estado')->nullable()->default(0);
			$table->string('cedula', 50)->nullable();
			$table->string('nombre', 100)->nullable();
			$table->string('telefono', 100)->nullable();
			$table->string('direccion', 150)->nullable();
			$table->string('tipo_equipo', 100)->nullable();
			$table->string('marca', 100)->nullable();
			$table->string('modelo', 100)->nullable();
			$table->string('serial', 100)->nullable();
			$table->string('estado_equipo', 100)->nullable();
			$table->string('diagnostico')->nullable();
			$table->float('presupuesto', 10, 0)->nullable();
			$table->float('anticipo', 10, 0)->nullable();
			$table->float('restante', 10, 0)->nullable();
			$table->date('fecha_reparado')->nullable();
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
		Schema::drop('bauches');
	}

}
