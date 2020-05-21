<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\User;

class ModificarPerfilRequest extends FormRequest
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
        $usuario = User::find($this->input('email'));
        return [
            'nombres'             => 'string',
            'apellidos'           => 'string',
            'identificacion'      => [Rule::unique('usuario')->ignore($usuario->email, 'email')],
            'fecha_nacimiento'    => 'date',
            'sexo'                => 'in:M,F',
            'celular'             => 'string',
            'direccion'           => 'string',
            'clave'               => 'confirmed',
            'permiso_correos'     => 'boolean',
            'pais'                => 'string',

            'avatar'              => 'image',
            'avatar_url'          => 'url',
        ];
    }
}
