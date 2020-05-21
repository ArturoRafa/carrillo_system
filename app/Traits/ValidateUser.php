<?php 

namespace App\Traits;

use Validator;

trait ValidateUser {

	public function validateDataUser($data, $piloto = false) {

		$validation = [
			'email'            => 'bail|required|email|unique:usuario,email',
			'identificacion'   => 'bail|required|unique:usuario,identificacion',
			'nombres'          => 'required|string',
			'apellidos'        => 'required|string',
			'fecha_nacimiento' => 'required|date',
			'sexo'             => 'required|in:M,F',
			'celular'          => 'required',
			'direccion'        => 'required|string',

			'avatar'           => 'image'
		];

		if ($piloto) {
			$validation['piloto.horas_vuelo']         = 'required|numeric';
			$validation['piloto.descripcion']         = 'string';
			$validation['piloto.telefono_secundario'] = 'string';
			$validation['piloto.estado']              = 'string';
			$validation['piloto.pais']                = 'string';
		}

		$validator = Validator::make($data, $validation);

		return $validator;

	}

	public function validatePiloto($data) {
		$validation = [
			'piloto.horas_vuelo'         => 'required|numeric',
			'piloto.descripcion'         => 'string',
			'piloto.telefono_secundario' => 'string',
			'piloto.estado'              => 'string',
			'piloto.pais'                => 'string',

		];

		$validator = Validator::make($data, $validation);

		return $validator;

	}

}