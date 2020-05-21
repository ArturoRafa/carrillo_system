<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\RegistrarUsuarioRequest;
use App\Http\Requests\ModificarPerfilRequest;

use App\Transformers\UserTransformer;

use App\User;

use JWTAuth;

class UserController extends Controller
{

    public function index() {

        $usuarios = fractal(
            User::paginate(10),
            new UserTransformer()
        );

        return response()->json($usuarios);
    }

    public function registrarUsuario(RegistrarUsuarioRequest $request) {
        $usuario = User::create($request->input());

        return response()->json([
            'ok' => 'Usuario creado',
            'usuario' => $usuario
        ]);
    }

    public function modificarUsuario(ModificarPerfilRequest $request) {
        $usuario = JWTAuth::parseToken()->authenticate();
        
        $usuario->update($request->input());

        return response()->json([
            'ok' => 'Usuario Actualizado',
            'usuario' => $usuario
        ]);

    }

}
