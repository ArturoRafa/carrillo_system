<?php

namespace App\Traits;

use App\Bitacora;

trait BitacoraTrait {

	public function registrarEnBitacora($origen, $email, $recurso, $parametros, $respuesta) {
		Bitacora::create([
			'origen'        => $origen,
			'fechahora'     => \Carbon\Carbon::now(),
			'email_usuario' => $email,
			'recurso'       => $recurso,
			'parametros'    => json_encode( $parametros ),
			'respuesta'     => json_encode( $respuesta  )
		]);
	}

}