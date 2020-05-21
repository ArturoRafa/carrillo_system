<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\Bauche\CreateRequestBauche;
use Carbon\Carbon;
use App\Bauche;

use JWTAuth;

class BaucheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if( $request->fecha ) {
            $bauche = Bauche::with('user')->where('fecha_ingreso', $request->fecha)
            ->get();
        return response()->json([
            'bauche'    =>  $bauche
        ]);
        } else {
            $bauche = Bauche::with('user')->get();
            return response()->json([
                'bauche'    =>  $bauche
            ]);
        }

    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestBauche $request) {
        /*dd($request);*/
        $bauche = Bauche::create($request->input());
        $bauche->update(['estado' => 0]);
        return response()->json([
            'bauche' => $bauche
        ]);
    }

    public function detalle($id) {
        $bauche = Bauche::with('user')->where('id',$id)->first();
        if( $bauche ) {
            return response()->json([
                'bauche'  =>  $bauche
            ]);
        } else {
            return response()->json([
                'error'  =>  'no_existe'
            ],404);
        }
    }



    public function detalle_user($cedula) {
        $bauche = Bauche::with('user')->where('cedula', 'V-'.$cedula)->first();
        if( $bauche ) {
            return response()->json([
                'bauche'  =>  $bauche
            ]);
        } else {
            $bauche = array();
            return response()->json([
                'bauche'  =>  $bauche
            ],404);
        }
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
        $bauche = Bauche::where('id',$id)->first();
        if( $bauche ) {
            if($request->input('estado') == 1){
                $fecha_ac = Carbon::now();
                Input::merge(['fecha_reparado' =>  $fecha_ac]);
            }
            $bauche->update($request->input());
            return response()->json(['bauche'=>$bauche]);
        } else {
            return response()->json(['error'=>'no_existe'],404);
        }
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
