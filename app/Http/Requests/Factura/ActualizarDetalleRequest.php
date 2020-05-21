<?php

namespace App\Http\Requests\Factura;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarDetalleRequest extends FormRequest
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

            'descripcion'                           =>  'nullable|string',
            'precio_unitario'                       =>  'numeric',
            'cantidad'                              =>  'numeric',
            'total'                                 =>  'numeric',
            'impuesto'                              =>  'numeric',
            'total_general'                         =>  'numeric',
            //
        ];
    }
}
