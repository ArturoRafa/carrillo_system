<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Traits\BitacoraTrait;

use App\DetalleFactura;
use App\User;

use JWTAuth;

class DetalleFacturaController extends Controller
{
    use BitacoraTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $detalle = DetalleFactura::find($id);
        if($detalle != null){
            $detalle->update(['status_delete' => 0]);

            $this->registrarEnBitacora(
            $request->ip(),
            JWTAuth::parseToken()->authenticate()->email,
            'Eliminar Detalle Factura',
            $detalle->toArray(),
            ['ok' => 'Detalle Factura eliminada']
            );

            return response()->json([
            'ok'         => 'Detalle Factura eliminado',
            'detalle'    => $detalle
            ]); 
        }else{
            return response()->json([
            'ok' => 'Detalle no existe',
            ]);
        }
        //
    }
}
