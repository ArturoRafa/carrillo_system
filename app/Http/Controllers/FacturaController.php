<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Factura\CreateRequest;
use App\Http\Requests\Factura\ActualizarRequest;
use App\Http\Requests\Factura\ActualizarDetalleRequest;

use App\Jobs\EnvioPushFacturaCreada;

use App\Traits\BitacoraTrait;
use App\Mail\FacturaCreadaMail;

use App\DetalleFactura;
use App\Factura;
use App\SolicitudServicio;
use App\User;
use App\Producto;

use JWTAuth;

class FacturaController extends Controller
{
    use BitacoraTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $usuario = JWTAuth::parseToken()->authenticate();
        $factura = Factura::with('detalleFactura')
        ->get();
       
         
        return response()->json(compact('factura'));
     
    }

    public function anularFactura(Request $request, $id) {

      $usuario = JWTAuth::parseToken()->authenticate();
      $facturas = Factura::where('id',$id)
      ->where('status_delete',1)->get();
      
      if( sizeof($facturas)>0 ) {
        if( $usuario->tipo_usuario == 1 || $usuario == 0 ) {

         $facturas[0]->update(['status' => '2']);

          $this->registrarEnBitacora(
            $request->ip(),
            $usuario->email,
            'Anular Factura',
            $facturas,
            [
              'ok' => 'Anular Factura',
              'Factura' => $facturas,
            ]
          );

          return response()->json([
            'factura' => $facturas
          ]);

        }
      } else {
        abort(404);
      }
      
    }

    public function detalle(Request $request, $id){


        $factura = Factura::with('detalleFactura')
        ->where('id',$id)
        ->get();
        

          return response()->json([
            'ok' => $factura
          ]);
    }

    public function anular( $id) {
        $factura = Factura::where('id',$id)->first();
        if( $factura) {
            $factura->update(['estado'=>0]);
            return response()->json([
                'factura'  => $factura
            ]);
        }   else {
            return response()->json(['error' => 'no_existe'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request){
      $usuario = JWTAuth::parseToken()->authenticate();
   
      $factura = Factura::create($request->input());

      if( $request->detalleFactura != null ) {
        foreach ($request->detalleFactura as $key ) {
            $producto = Producto::where('id',$key['id_producto'])->first();
            if( $producto ) {
                if( $producto->cantidad_disponible > $key['cantidad']) {
                    $actual = $producto->cantidad_disponible - $key['cantidad'];
                    $producto->update( ['cantidad_disponible'=> $actual]);
                } else {
                    return response()->json(['ok' => 'producto_no_disponible',
                        'producto' => $producto ],401); 
                }
            } else {
                 return response()->json(['ok' => 'producto_no_encontrado'],404); 
            }
        }
        

        $factura->detalleFactura()->createMany($request->detalleFactura);
        $factura->load('detalleFactura');
      }
      
      return response()->json(['factura' => $factura]); 
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

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
        $factura = Factura::find($id);
        if( sizeof($factura)>0){
            $factura->update(['status_delete' => 0]);

            $this->registrarEnBitacora(
            $request->ip(),
            JWTAuth::parseToken()->authenticate()->email,
            'Eliminar Factura',
            $factura->toArray(),
            ['ok' => 'Factura eliminada']
            );

            return response()->json([
            'factura' => $factura
            ]); 
        }else{
          abort(404);
            /*return response()->json([
            'ok' => 'Factura no existe',
            ]);*/
        }
        //
    }
}
