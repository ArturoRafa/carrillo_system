<?php

namespace App\Http\Requests\Notificacion;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'descripcion'       => 'required|string',
            'tipo'              => 'required|in:0,1,2',
            'email_usuario'     => 'required_if:tipo,0|exists:usuario,email',
            'id_solicitud'      => 'required|exists:solicitud_servicio,id',
        ];
    }
}
