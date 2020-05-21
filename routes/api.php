<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'LoginController@authenticate');

Route::prefix('usuarios')->group(function() {
    Route::get('/', 'UserController@index');
    Route::post('registrar', 'UserController@registrarUsuario');
});

Route::prefix('usuarios')->middleware('jwt.auth')->group(function() {

    Route::post('actualizar', 'UserController@modificarUsuario');

});