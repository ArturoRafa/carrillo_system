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


Route::post('login',                    'LoginController@authenticate');
Route::get('embarcaciones/{filter?}',   'EmbarcacionController@index');
Route::get('ofertas',         'OfertasController@index');
Route::get('seguros',                   'SeguroController@index');


Route::prefix('public')->group(function() {

    /*Route::prefix('viajes')->group(function () {
        Route::get('buscar', 'ViajeController@searchViajesPublicos');
        Route::get('buscar-ubicacion', 'ViajeController@searchUbicacion');
    });*/

    Route::prefix('usuarios')->group(function() {
        Route::post('existe',                   'UserController@existeEmail');
        Route::post('registrar',                'UserController@registrarUsuario');
        Route::post('registrar-social-account', 'UserController@registerUserSocialAccount');
        Route::post('forgot-password',          'UserController@forgotPassword');
        Route::post('reset-password',           'UserController@resetPassword');
        Route::post('verify-social-account',    'UserController@verifySocialAccount');
    });
});



Route::middleware('jwt.auth')->group(function() {

    /**
     * Grupos de funcionalidades extra para cada
     * resource
     */
     
    //productos
    Route::prefix('productos')->group(function (){
        Route::post('estado/{id}',     'ProductoController@cambiarEstado');
        Route::get('detalle/{id}',     'ProductoController@detalle');
    });

    Route::prefix('bauches')->group(function (){
        Route::post('estado/{id}',     'ProductoController@cambiarEstado');
        Route::get('detalle/{id}',     'BaucheController@detalle');
        Route::get('detalle_user/{cedula}',     'BaucheController@detalle_user');
    });
    

    // Notificaciones

    Route::prefix('notificaciones')->group(function() {
        Route::post('leida/{id}', 'NotificacionesController@leida');
        Route::get('detalle/{id}', 'NotificacionesController@detalle');
    });

    
    //facturas
    Route::prefix('facturas')->group(function() {
       Route::get('detalle/{id}',         'FacturaController@detalle');
       Route::post('actualizar/{id}',   'FacturaController@actualizarTodo');
       Route::post('actualizar-detalle/{id}',   'FacturaController@actualizarDetalle');
       Route::post('usuario','FacturaController@facturasUsuario');
       Route::post('anular/{id}','FacturaController@anular');
    });
   
   

    // Usuarios
    Route::prefix('usuarios')->group(function() {
        Route::get('/',                           'UserController@index');
        Route::post('/eliminar',          'UserController@eliminarUsuario');
        Route::post('agregar',            'UserController@registrarUsuarioAdmin');
        Route::post('actualizar',                 'UserController@modificarUsuario');
        Route::post('actualizar-token',           'UserController@actualizarToken');
        Route::post('admin/{usuario}/actualizar', 'UserController@adminModificarUsuario');
        // Historial de transacciones
       Route::get('historial-compra/{id}','UserController@historialCompraUsusario');

        //facturas
        Route::get('listar-facturas','UserController@listFacturas');
        //Stripe
       
        Route::get('detalle/{id}', 'UserController@obtenerUsuario');
        Route::post('pagar/{id}',           'UserController@pagarFactura');
        Route::post('admin/ventas', 'UserController@ventasDia');
        Route::post('editar/{id}', 'UserController@editar');
        Route::post('compraRapida', 'UserController@compraRapida');

    });
    



    /**
     * Resources
     * Métodos básicos de CRUD
     */
    /*Route::resource('aeropuertos',             'AeropuertoController');
    Route::resource('comodidades',             'ComodidadesController');*/
    Route::resource('calificaciones',          'CalificacionesController');
    /*Route::resource('viajes',                  'ViajeController');*/
    Route::resource('embarcaciones',           'EmbarcacionController');
    Route::resource('fotos',                   'FotoController');
    Route::resource('propietarios',            'PropietarioController');
    /*Route::resource('pilotos',                 'PilotoController');*/
    Route::resource('notificaciones',          'NotificacionesController');
    /*Route::resource('pasajeros-frecuentes',    'PasajeroFrecuenteController');
    Route::resource('vuelos-medida',           'VueloMedidaController');*/
    Route::resource('seguros',                 'SeguroController');
    Route::resource('solicitud-servicios',     'SolicitudServicioController');
    Route::resource('ofertas',                 'OfertasController');
    Route::resource('personas',                'PersonasController');
    Route::resource('facturas',                'FacturaController');
    Route::resource('detalle-factura',         'DetalleFacturaController');
    Route::resource('productos',         'ProductoController');
    Route::resource('bauches',         'BaucheController');



});