<?php

namespace App\Http\Requests\Factura;

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
            'num_factura'                           =>  'required|string',
            'fecha_facturacion'                     =>  'required|date',
            'cedula_usuario'                        =>  'required|string',
            'nombre'                                =>  'required|string',
            'email'                                 =>  'nullable|email',
            'telefono'                              =>  'nullable|string',
            'estado'                                =>  'numeric',
            'total'                                 =>  'numeric',
            'detalleFactura'                        =>  'nullable',
            'detalleFactura.*.id_producto'          =>  'numeric',
            'detalleFactura.*.id_factura'           =>  'numeric',
            'detalleFactura.*.precio'               =>  'numeric',
            'detalleFactura.*.descripcion'          =>  'string',
            'detalleFactura.*.cantidad'             =>  'numeric',
            //
        ];
    }
}
