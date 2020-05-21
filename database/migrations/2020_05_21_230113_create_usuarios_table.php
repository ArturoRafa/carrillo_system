<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsuariosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usuarios', function(Blueprint $table)
		{
			$table->string('nombre', 200)->nullable();
			$table->string('cedula', 200)->primary();
			$table->string('email', 200);
			$table->integer('tipo_usuario')->nullable();
			$table->timestamps();
			$table->string('password', 200)->nullable();
			$table->string('telefono', 50)->nullable();
			$table->boolean('status_delete')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('usuarios');
	}

}
