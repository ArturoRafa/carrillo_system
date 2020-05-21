<?php

namespace App\Http\Requests\Factura;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarRequest extends FormRequest
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
            'id_solicitud'                          =>  'nullable|exists:solicitud_servicio,id',
            'status'                                =>  'numeric',
            'identificacion'                        =>  'nullable|string',
            'nombres'                               =>  'nullable|string',
            'apellidos'                             =>  'nullable|string',
            'direccion'                             =>  'nullable|string',
            'email'                                 =>  'nullable|email',
            'telefono'                              =>  'nullable|string',
            'descripcion'                           =>  'nullable|string',
            'total'                                 =>  'numeric',
            'impuesto'                              =>  'numeric',
            'total_general'                         =>  'numeric',
            //
        ];
    }
}
