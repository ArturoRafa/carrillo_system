<?php

namespace App\Http\Requests\Bauche;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequestBauche extends FormRequest
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
            'fecha_ingreso'     =>      'nullable|date',
            'descripcion'       =>      'nullable|string',
            'fecha_salida'      =>      'nullable|date',
            'cedula_usuario'     =>     'nullable|string',
            'estado'             =>     'nullable|numeric',
            'cedula'            =>      'nullable|string',
            'nombre'            =>      'nullable|string',
            'telefono'          =>      'nullable|string',
            'direccion'         =>      'nullable|string',
            'tipo_equipo'       =>      'nullable|string',
            'marca'             =>      'nullable|string',
            'modelo'            =>      'nullable|string',
            'serial'            =>      'nullable|string',
            'estado_equipo'     =>      'nullable|string',
            'diagnostico'       =>      'nullable|string',
            'presupuesto'       =>      'nullable|numeric',
            'anticipo'          =>      'nullable|numeric',
            'restante'          =>      'nullable|numeric'
        ];
    }
}
