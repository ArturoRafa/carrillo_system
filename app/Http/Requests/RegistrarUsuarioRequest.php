<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombres' => 'required|string',
            'identificacion' => 'required|unique:usuario,identificacion',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'email' => 'required|email|unique:usuario,email',
            'celular' => 'required',
            'direccion' => 'required|string',
            'password' => 'required|confirmed',
            'permiso_correos' => 'required|boolean',
        ];
    }
}
