<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class RegisterSocialMediaRequest extends FormRequest
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
            'nombres'             => 'required|string',
            'apellidos'           => 'required|string',
            'identificacion'      => 'required|unique:usuario,identificacion',
            'fecha_nacimiento'    => 'required|date',
            'sexo'                => 'required|in:M,F',
            'email'               => 'required|email|unique:usuario,email',
            'celular'             => 'required',
            'direccion'           => 'required|string',
            'permiso_correos'     => 'required|boolean',
            'social_account'      => 'required|string',
            'tipo_identificacion' => 'required|string',
            'tipo_usuario'        => 'numeric',

            'avatar'              => 'image',
            'avatar_url'          => 'url',

            'expo_token'          => 'string'
        ];
    }
}
