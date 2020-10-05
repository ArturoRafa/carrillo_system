<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Http\Requests\RegistrarUsuarioRequest;
use App\Http\Requests\ModificarPerfilRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;


use App\Transformers\UserTransformer;
use App\Transformers\HistorialTransformer;



use App\User;
use App\Factura;
use App\Producto;
use App\DetalleFactura;
use App\Bauche;
use Carbon\Carbon;
use JWTAuth;

class UserController extends Controller
{


   /* public function existeEmail(Request $request) {

        $email = User::find($request->email);

        return response()->json([
            'existe' => !is_null($email),
        ]);
    }*/

    public function actualizarToken() {
        $usuario = JWTAuth::parseToken()->authenticate();

        $token = JWTAuth::fromUser($usuario);

        return response()->json([
            'ok'    => 'Token actualizado',
            'token' => $token
        ]);
    }

    public function editar(Request $request, $id){
        $usuario = User::find($id);
        $usuario->update($request->input());
        return response()->json(['usuario'=>$usuario]);
    }

    public function index(Request $request) {

        $usuarios = User::where("status_delete","!=", 0)->get();


        return response()->json([
            'usuarios'    => $usuarios
        ]);
    }

   public function historialCompraUsusario($id) {

    $historial = User::with('factura')->where('cedula',$id)->first();

    return response()->json(["historial" => $historial]);
   }

   public function ventasDia(Request $request) {


    $carbon = new \Carbon\Carbon();
    $date = $carbon->now();
    $date = $date->format('Y-m-d');
    /*dd( $date);*/
    $total = 0;

    if( $request->fecha ) {
        $factura = Factura::where('fecha_facturacion',$request->fecha )
        ->where('estado',1)->get();
    
        foreach ($factura as $key ) {
            $total = $total + $key->total;
        }

        return response()->json([
        'facturas' => $factura,
        'total' =>$total 
        ]);
    } else {

         $factura = Factura::where('fecha_facturacion',$date )
        ->where('estado',1)->get();
    
        foreach ($factura as $key ) {
            $total = $total + $key->total;
        }

        return response()->json([
        'facturas' => $factura,
        'total' =>$total 
        ]);

    }

   }

    public function obtenerUsuario(Request $request,$id){
        $usuario = JWTAuth::parseToken()->authenticate();
        $usuarioDetalle = User::find($id);
        return response()->json([
            'usuario' => $usuarioDetalle
        ]);
    }

     public function compraRapida(Request $request){
        $usuario = JWTAuth::parseToken()->authenticate();
        $producto = Producto::where('codigo_barras',$request->codigo)->first();

        if( $producto ) {
            if( $producto->cantidad_disponible >= $request->cantidad ) {
                $cantidad = $producto->cantidad_disponible - $request->cantidad;
                $producto->update([ 'cantidad_disponible' => $cantidad ]);

                $carbon = new \Carbon\Carbon();
                $date = $carbon->now();
                $date = $date->format('Y-m-d');

                $factura = new Factura();
                $factura->fecha_facturacion = $date;
                $factura->cedula_usuario = $usuario->cedula;
                $factura->total = ($producto->precio_venta*$request->cantidad);
                $factura->save();

                $detallefactura = new DetalleFactura();
                $detallefactura->id_producto = $producto->id;
                $detallefactura->id_factura = $factura->id;
                $detallefactura->precio = $producto->precio_venta;
                $detallefactura->cantidad = $request->cantidad;
                $detallefactura->save();


                return response()->json([
                    'ok'      => 'Inventario actualizado',
                    'producto' => $producto,    
                ]);
            } else {
                return response()->json([
                    'error'      => 'cantidad_excedida'
                ],401);
            }
        } else {
            return response()->json([
                'error'      => 'producto_no_existe'
            ],401);
        }

     }
    public function registrarUsuario(RegistrarUsuarioRequest $request) {

        
        $usuarios = User::find($request->input('cedula'));
        if(!$usuarios){
            
            $input = $request->input();
            $usuarios = User::create($input);
            
        
        }else{

            $usuarios->nombre = $request->input('nombre');
            $usuarios->email = $request->input('email');
            $usuarios->telefono = $request->input('telefono');
            $usuarios->status_delete = 1;
            $usuarios->updated_at = Carbon::now();
            $usuarios->update();

        }

        return response()->json([
            'ok'      => 'Usuario creado',
            'usuario' => $usuarios,
            
        ]);
    }

    public function eliminarUsuario(Request $request){
        $uso = JWTAuth::parseToken()->authenticate();
        if($uso->tipo_usuario == 1) {
           $usuarios = User::where("tipo_usuario", 0)->find($request->cedula);
            $usuarios->update(['status_delete' => 0]);

            return response()->json([
                'usuarios' => $usuarios
            ]); 
        } else {
            abort(401);
        }
        
    }




    public function pagarFactura( Request $request, $id ) {

             
    }

    public function listFacturas(Request $request){

        $usuario = JWTAuth::parseToken()->authenticate();
        $facturas = Factura::with('user','detalleFactura')->where('email_usuario',$usuario->email)->paginate(15);

        return response()->json(compact('facturas'));
    }



}
