<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequestProducto extends FormRequest
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
            'codigo_barras'     =>  'nullable|string',
            'estado'            =>  'nullable|numeric',
            'precio_venta'      =>  'nullable|numeric',
            'precio_compra'     =>  'nullable|numeric',
            'marca'             =>  'nullable|string',
            'modelo'            =>  'nullable|string',
            'color'             =>  'nullable|string',
            'garantia'          =>  'nullable|string',
            'descripcion'       =>  'nullable|string',
            'imagen'            =>  'nullable|image',
            'tipo_producto'     =>  'nullable|numeric',
            'cantidad_disponible' => 'nullable|string'
            //
        ];
    }
}
