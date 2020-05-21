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
            'nombre'             => 'nullable|string',
            'cedula'             => 'nullable|string',
            'email'              => 'nullable|email',
            'tipo_usuario'       => 'nullable|numeric',
            'password'           => 'nullable|string',
            'telefono'           => 'nullable|string'
        ];
    }
}
