<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Producto\CreateRequestProducto;

use App\DetalleFactura;
use App\Producto;

use JWTAuth;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        /*dd($user->tipo_usuario);*/

        if( $user->tipo_usuario == 1 ){
            if( $request->tipo!= null && $request->codigo !=null) {
            
                $producto = Producto::where('tipo_producto',$request->tipo)
                ->where('codigo_barras', $request->codigo)->get();
            } elseif ( $request->tipo!=null && $request->codigo==null ) {
                
                $producto = Producto::where('tipo_producto',$request->tipo)
                ->get();
            } elseif ( $request->tipo==null && $request->codigo!=null ) {
                
                $producto = Producto::where('codigo_barras', $request->codigo)
                ->get();
            } else {

                $producto = Producto::get();
            }

            return response()->json([
                'productos' =>$producto 
            ]);
        }
        else {
            if( $request->tipo!= null && $request->codigo !=null ) {
                $producto = Producto::where('tipo_producto',$request->tipo)
                ->where('codigo_barras', $request->codigo)
                ->where('estado',1)->get();
            } elseif ( $request->tipo!=null && $request->codigo==null ) {

                $producto = Producto::where('tipo_producto',$request->tipo)
                 ->where('estado',1)
                ->get();
            } elseif ( $request->tipo==null && $request->codigo!=null ) {
                /*dd($request->tipo);*/
                $producto = Producto::where('codigo_barras', $request->codigo)
                 ->where('estado',1)
                ->get();
            } else {

                $producto = Producto::where('estado',1)->get();
            }

            return response()->json([
                'productos' =>$producto 
            ]);
        }
       
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestProducto $request){

        $user = JWTAuth::parseToken()->authenticate();

        $productos = Producto::where('codigo_barras', $request->input('codigo_barras'))->first();
        if($productos){
          return response()->json([
                'nok'    => [] 
            ]);  
        }
        if( $user->tipo_usuario == 1) {
            $producto = Producto::create($request->input());

            if ($request->has('imagen')){
        /*$fileName = Storage::disk('public')->put('productos', $request->imagen);*/
                $fileUrl = Storage::disk('public')->put('productos', $request->imagen);
                $urlFile = env('APP_URL').'/storage/'.$fileUrl;
                $producto->update(['imagen' => $urlFile]);
            }
            return response()->json([
                'ok'    => $producto 
            ]);
        } else {
            abort(401);
        }
        
    }

    public function cambiarEstado(Request $request, $id) {
        $producto = Producto::where('id',$id)->first();
        if( $producto) {
            $producto->update(['estado'=>$request->estado]);
            return response()->json([
                'producto'  => $producto
            ]);
        }   else {
            return response()->json(['error' => 'no_existe'], 404);
        }
    }

    public function detalle($id){
        $producto = Producto::where('codigo_barras',$id)->first();
        if($producto ){
            return response()->json([
                'productos' =>  $producto
            ]);
        } else {
            return response()->json(['error' =>  'no_existe'],404);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $producto = Producto::where('id',$id)->first();
        if( $producto) {
            $producto->update($request->input());
            return response()->json([
                'producto'  => $producto
            ]);
        }   else {
            return response()->json(['error' => 'no_existe'], 404);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
