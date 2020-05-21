<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Notificacion\CreateRequest;



use App\Notificacion;
use App\Bauche;
use JWTAuth;

class NotificacionesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->subMonth(2);
        $date = $date->format('Y-m-d');
        
        $bauche = Bauche::where('estado', 1)->whereDate('fecha_ingreso','<=',$date)
        ->get();
        /*dd($bauche->cedula_usuario);*/
        if(!$bauche->isEmpty()){
            foreach ($bauche as $key) {
                $notificacion_all = Notificacion::where('id_bauche' , $key->id)->first();
                if(!$notificacion_all){
                $notificacion = Notificacion::create([                
                    'tipo' => 0,
                    'id_bauche' => $key->id,
                    'estado' => 0,
                    'cedula' => $key->cedula
                 ]);
                }
                
            }
            $notificaciones = Notificacion::where('estado',0)->with('bauches')->get();
            return response()->json(['ok'=>$notificaciones, 'cantidad'=> sizeof($notificaciones)]);
        }else{
             return response()->json(['ok'=> "0", 'cantidad'=> "0"]);
        }
          
        
    }
    public function detalle($id){
        $notificacion = Notificacion::find($id);
        return response()->json([
            'notificacion' => $notificacion
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $notificacion = Notificacion::create($request->input());

        $this->registrarEnBitacora(
            $request->ip(),
            null,
            'crear notificacion',
            $request->input(),
            [
                'ok' => 'Notificación creada',
                'notificacion' => $notificacion
            ]
        );

        return response()->json([
            'ok' => 'Notificación creada',
            'notificacion' => $notificacion
        ]);
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
    public function destroy($id)
    {
        //
    }

    public function leida(Request $request, $id) {

        $notificacion = Notificacion::find($id);
        if(!$notificacion){
             return response()->json(['error' =>  'Notificación no existe']);
        }
        $notificacion->update([
            'estado' => 1
        ]);
        
        

        return response()->json([
            'ok' => 'Notificación leída',
            'notificacion' => $notificacion
        ]);
    }
}
