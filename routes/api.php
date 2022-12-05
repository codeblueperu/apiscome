<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermisosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\ComprobantesController;
use App\Http\Controllers\Api\CreditoController;
use App\Http\Controllers\Api\GastoController;
use App\Http\Controllers\Api\ServiceGeneralesController;
use App\Http\Controllers\Api\SucursalesController;
use App\Http\Controllers\Api\PersonaController;
use App\Http\Controllers\Api\FamiliaCategoriaController;
use App\Http\Controllers\Api\FichaTecnicaController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\TallaController;
use App\Http\Controllers\Api\CuponProductoController;
use App\Http\Controllers\Api\ImagenProductoController;
use App\Http\Controllers\Api\TbCajaController;
use App\Http\Controllers\Api\TbDescuentoVentaController;
use App\Http\Controllers\Api\TbClienteController;
use App\Http\Controllers\Api\TipoPagoController;
use App\Http\Controllers\Api\VentaProductoController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('login',[AuthController::class,'getLogin']);
    Route::post('createcount',[AuthController::class,'createcountuser']);
    Route::post('datosauth',[AuthController::class,'me']);
    Route::get('getlistadatospersonales',[PersonaController::class,'listaDatospersonales']);
    Route::post('logout',[AuthController::class,'logout']);
    Route::post('refresh',[AuthController::class,'refresh']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'mantenimiento'
], function ($router) {
    /**FAMILIA CATEGORIA */
    Route::get('familiacategoria',[FamiliaCategoriaController::class,'listarTodo']);
    Route::post('familiacategoria',[FamiliaCategoriaController::class,'registrar']);
    Route::get('familiacategoria/{id}',[FamiliaCategoriaController::class,'buscarID']);
    Route::put('familiacategoria/{id}',[FamiliaCategoriaController::class,'update']);
    Route::delete('familiacategoria/{id}',[FamiliaCategoriaController::class,'destroy']);
    /** CATEGORIA */
    Route::get('categoria',[CategoriaController::class,'index']);
    Route::post('categoria',[CategoriaController::class,'store']);
    Route::get('categoria/{id}',[CategoriaController::class,'show']);
    Route::put('categoria/{id}',[CategoriaController::class,'update']);
    Route::delete('categoria/{id}',[CategoriaController::class,'destroy']);
    Route::get('categoria/familia/{id}',[CategoriaController::class,'obtenerCatFamilia']);
    /**MARCA */
    Route::get('marca',[MarcaController::class,'index']);
    Route::post('marca',[MarcaController::class,'store']);
    Route::get('marca/{id}',[MarcaController::class,'show']);
    Route::post('marca/{id}',[MarcaController::class,'update']);
    Route::delete('marca/{id}',[MarcaController::class,'destroy']);
    Route::get('marca/{codcategoria}/listaporcategoria',[MarcaController::class,'getBuscarPorCategorias']);
    /**PRODUCTO */
    Route::get('producto/estado/{status}',[ProductoController::class,'index']);
    Route::post('producto',[ProductoController::class,'store']);
    Route::get('producto/{id}',[ProductoController::class,'show']);
    Route::post('producto/{id}',[ProductoController::class,'update']);
    Route::delete('producto/{id}',[ProductoController::class,'destroy']);
    //Route::resource('producto',ProductoController::class);
    Route::post('otherproductoflask',[ProductoController::class,'otherProductoFlask']);
    Route::get('buscarproductocodigobarra/{codbarra}',[ProductoController::class,'buscarProductoCodigoBarra']);
    Route::get('codigobarra/venta/{code}',[ProductoController::class,'addproductoCodigoBarra']);

    /**TALLAS */
    Route::post('talla',[TallaController::class,'store']);
    Route::get('tallas/{idproducto}',[TallaController::class,'show']);
    Route::delete('tallas/{idproducto}',[TallaController::class,'destroy']);
    Route::put('talla/{id}',[TallaController::class,'update']);
    /**COLORES */
    Route::post('color',[ColorController::class,'store']);
    Route::get('colores/{idproducto}',[ColorController::class,'show']);
    Route::delete('colores/{idproducto}',[ColorController::class,'destroy']);
    Route::put('color/{id}',[ColorController::class,'update']);
    /**FICHA TECNICA */
    Route::post('fichatecnica',[FichaTecnicaController::class,'store']);
    Route::get('fichatecnica/{idproducto}',[FichaTecnicaController::class,'show']);
    Route::delete('fichatecnica/{idproducto}',[FichaTecnicaController::class,'destroy']);
    Route::put('fichatecnica/{id}',[FichaTecnicaController::class,'update']);
    /**CUPON PRODUCTO */
    Route::get('cupon',[CuponProductoController::class,'index']);
    Route::post('cupon',[CuponProductoController::class,'store']);
    Route::get('cupon/{id}',[CuponProductoController::class,'show']);
    Route::put('cupon/{id}',[CuponProductoController::class,'update']);
    Route::delete('cupon/{id}',[CuponProductoController::class,'destroy']);
    /**DETALLE IMAGEN */
    Route::post('imagen',[ImagenProductoController::class,'store']);
    Route::delete('imagen/{id}',[ImagenProductoController::class,'destroy']);
    /** CAJA **/

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'operaciones'
], function ($router) {
    /** CAJA **/
    Route::get('caja',[TbCajaController::class,'index']);
    Route::post('caja',[TbCajaController::class,'store']);
    Route::get('caja/{id}',[TbCajaController::class,'show']);
    Route::get('caja/verificarcaja/{estatus}',[TbCajaController::class,'validaestadocaja']);
    Route::get('caja/filtrar/{fechaone}/{fechatwo}',[TbCajaController::class,'datoscaja']);
    Route::put('caja/{id}',[TbCajaController::class,'update']);
    /* DECUENTO VENTA */
    Route::resource('descuentoventa',TbDescuentoVentaController::class);
    Route::get('descuentoventa/filter/{inicio}/{fin}',[TbDescuentoVentaController::class,'buscarcuponesporFecha']);
    /* CUPONES DESCUENTO */
    Route::get('buscarcuponDescuento/{id}',[TbDescuentoVentaController::class,'buscarcuponDescuento']);
    /* CLIENTES */
    Route::resource('clientes',TbClienteController::class);
    Route::get('buscarcliente/{id}',[TbClienteController::class,'bucarClienteNumeroDocumento']);
    /* TIPOPAGO */
    Route::resource('tipopago',TipoPagoController::class);
    /* VENTAS */
    Route::resource('venta',VentaProductoController::class);
    Route::get('data/venta/{inicio}/{fin}',[VentaProductoController::class,'misventas']);
    Route::post('data/venta/baja/{codventa}',[VentaProductoController::class,'darBajaVenta']);
    /* GASTOS */
    Route::resource('gasto',GastoController::class);
    Route::get('gasto/filter/{inicio}/{fin}',[GastoController::class,'buscarporfecha']);

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'modulocredito'
], function ($router) {
    Route::resource('credito',CreditoController::class);
    Route::get('buscardetalledeuda/{idcredito}',[CreditoController::class,'buscaDeudaCliente']);
    Route::post('guardarnuevoingreso',[CreditoController::class,'sumaradelantocliente']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'setting'
], function ($router) {
    Route::get('listatipodocumentos',[ServiceGeneralesController::class,'listaTipoDocumentos']);
    Route::get('listacargosusuario',[ServiceGeneralesController::class,'listaCargos']);
    Route::get('listasucursales',[SucursalesController::class,'listasucursales']);
    Route::get('buscardnipersona/{dni}',[ServiceGeneralesController::class,'buscarPersonadni']);
    Route::get('buscaremailpersona/{email}',[ServiceGeneralesController::class,'buscarPersonaEmail']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'security'
], function ($router) {
    Route::post('crearcuentausuario',[PersonaController::class,'crearCuentaUsuarioSistema']);
    Route::get('listausuario',[PersonaController::class,'listaDatospersonales']);
    Route::get('buscarpersonaid/{codpersona}',[PersonaController::class,'buscarpersonaID']);
    Route::put('editadatausuario/{codpersona}',[PersonaController::class,'editarDatosUsuario']);
    Route::delete('eliminarpersona/{codpersona}',[PersonaController::class,'eliminarCuenta']);
    Route::get('menuprincipales',[ServiceGeneralesController::class,'listaMenuPrincipal']);
    Route::get('submenu/{cargo}/{grupo}',[ServiceGeneralesController::class,'listaSubMenu']);
    Route::get('usuariosucursal/{codsucursal}',[UserController::class,'listausuarioSucursal']);
    Route::post('guardarpermisomenu',[ServiceGeneralesController::class,'guardarPermisoMenu']);
    Route::get('menuaccesouserlogin',[PermisosController::class,'menusUsuarioLogin']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'moduloreportes'
], function ($router) {
    Route::get('printcomprobant/{id}',[ComprobantesController::class,'generarTikenVenta']);
    Route::get('printcuadrecaja/{id}',[ComprobantesController::class,'reportecuadreCaja']);
    Route::get('pruebasql/{id}',[ComprobantesController::class,'pruebaScript']);
});
