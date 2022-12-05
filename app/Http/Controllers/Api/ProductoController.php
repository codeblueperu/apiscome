<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Producto\StoreRequest;
use App\Http\Requests\Producto\UpdateRequest;
use App\Models\TbCompraProducto;
use Illuminate\Support\Facades\Storage;
use App\Models\TbProducto;
use Illuminate\Http\Request;
use App\Models\TbDetalleTallaProducto;
use App\Models\TbDetalleColorProducto;
use App\Models\TbFichaTecnicaProducto;
use Illuminate\Support\Facades\DB;
use App\Models\TbImagenProducto;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }


    public function index($status)
    {
        try {
            $productos= null;
            if($status == '2'){
                $productos = DB::select('SELECT * FROM vw_producto_compra WHERE estado_producto = "1" ');
            }else if($status == '3'){
                $productos = DB::select('SELECT * FROM vw_producto_compra where estado_producto = "1" AND  estado_compra = "1" group by code_barra order by cod_compra asc;');
            }else if($status == 'KARDEX'){
                $productos = DB::select('SELECT * FROM vw_kardex');
            }
            else{
                $productos = DB::select('SELECT * FROM vw_producto_compra WHERE estado_producto = "1" AND  estado_compra = ?',[$status]);
            }

            return response()->json([
                'error'=> false,
                'message'=> 'Lista de registros.',
                'status'=> 200,
                'data'=> $productos
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }
    }

    public function store(Request $request)
    {
        /*:::::::::::::::: INICIAMOS LA TRANSACCION ::::::::::::::*/
        DB::beginTransaction();
        /* ::::::::::::::: ENGLOBAMOS TODO EN UN TRY ::::::::::::: */
        try {
            /* :::::::: GUARDAMOS DATOS DEL PRODUCTO :::::::::::: */
            $newProducto = json_decode($request->producto);

            /* ************************************************* */
                $code_producto = $newProducto->cod_producto;
            /* ************************************************* */

            /* VALIDAR SI ES UN NUEVO PRODUCTO O UN NUEVO INGRESO DE COMPRA */
            if($newProducto->cod_producto){
                $producto = TbProducto::find($newProducto->cod_producto);
                $producto->code_barra= $newProducto->code_barra;
                $producto->nombre_producto= $newProducto->titulo_producto;
                $producto->descripcion_corta= $newProducto->descripcion_corta;
                $producto->descripcion_larga= '';
                $producto->estado= $newProducto->estado;
                $producto->cod_categoria= $newProducto->cod_categoria;
                $producto->cod_marca= $newProducto->cod_marca;
                $producto->save();
            }else{
                /* VALIDAMOS QUE EL COD BARRA Y COD LOTE NO SE REPITA */
                $validProdCodBarra = TbProducto::where('code_barra',$newProducto->code_barra)->get();
                if(count($validProdCodBarra) > 0){
                    DB::rollBack();
                    return response()->json(['message'=> 'El codigo de barrra <b>'.$newProducto->code_barra.'</b>, ya se encuentra en uso.'],409);
                }

                $producto = TbProducto::create([
                    'code_barra' => $newProducto->code_barra,
                    'nombre_producto' => $newProducto->titulo_producto,
                    'descripcion_corta' => $newProducto->descripcion_corta,
                    'descripcion_larga' => '',
                    'cod_categoria' => $newProducto->cod_categoria,
                    'cod_marca' => $newProducto->cod_marca,
                    'cod_sucursal' => 1,
                    'estado' => $newProducto->estado
                ]);

                /* ************************************************ */
                    $code_producto = $producto->cod_producto;
                /* ************************************************ */
            }

            /* REGISTRAMOS LA NUEVA COMPRA */
            $newcompra = TbCompraProducto::create([
                'nombre_producto' => $newProducto->titulo_producto . ' ' . $newProducto->descripcion_corta,
                'stock_ingreso' => $newProducto->stock,
                'stock_egreso' => $newProducto->stock,
                'precio_compra' => $newProducto->precio_compra,
                'precio_venta_unidad' =>$newProducto->precio_unitario,
                'precio_venta_cantidad' => $newProducto->precio_mayor,
                'cantidad_venta_mayor' => $newProducto->cantidad_venta_mayor,
                'numero_lote' => $newProducto->numero_lote,
                'fecha_compra' => $newProducto->fecha_compra,
                'fecha_elaboracion' => $newProducto->fecha_elaboracion,
                'fecha_caduca' => $newProducto->fecha_caduca,
                'cod_producto'=> $code_producto,
                'estado' => $newProducto->estado
            ]);
            /* ****************************************************** */
                $code_compra = $newcompra->cod_compra;
            /* ****************************************************** */

            /* :::::::::: PRODEMOS A REGISTAR LAS TALLAS ::::::::::: */
            foreach (json_decode($request->tallas) as $value) {
              $modelTalla = new TbDetalleTallaProducto();
              $modelTalla->cantidad = $value->cantidad;
              $modelTalla->cod_compra = $code_compra;
              $modelTalla->talla = $value->talla;
              $modelTalla->save();
            }
            /* :::::::: PROCEDEMOS A REGISTRAR LOS COLORES ::::::: */
            foreach (json_decode($request->colores) as $value) {
                $modelColor = new TbDetalleColorProducto();
                $modelColor->color = $value->color;
                $modelColor->cantidad = $value->cantidad;
                $modelColor->cod_compra = $code_compra;
                $modelColor->save();
            }

            /* :::::::::: PROCEDEMOS A REGISTRAR LA FICHA TECNICA ::::::: */
            $rqficha = json_decode($request->fichaproducto);
            $ficha = new TbFichaTecnicaProducto();
            $ficha->body_ficha = $rqficha->body_ficha;
            $ficha->cod_compra = $code_compra;;
            $ficha->save();

            /* :::::::::: PROCEDEMOS A REGISTRAR LAS IMAGENES ::::::::::: */
            $i = 0;
            if($request->hasFile('images')){
                foreach ($request->file('images') as $image) {
                    $modelimage = new TbImagenProducto();
                    $nombre_imagen = $image->getClientOriginalName();
                    $destinationPath = "public/images/catalogo";
                    Storage::putFileAs($destinationPath, $image, 'pd'.$nombre_imagen);
                    $modelimage->image_name = 'pd'.$nombre_imagen;
                    $modelimage->orden =$i;
                    $modelimage->cod_producto = $code_producto;
                    $modelimage->save();
                    $i++;
                }
            }

            /* :::: LANZAMOS EL COMMIT :::::: */
            DB::commit();
            /* :::: RETORNAMOS LA RESPUESTA :::::: */
            return response()->json([
                'error'=> false,'message'=> 'Producto registrado correctamente',
                'status'=> 200,'data' => null
           ],200);

        } catch (\Throwable $th) {
            /* :::::::::::: REVERTIMOS TODO EN CASO DE UN ERROR::::: */
            DB::rollBack();
            /* :::::::::::: NOTIFICAR AL USUARIO EL ERROR :::::::::: */
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }
    }

    public function show($id)
    {
        try {
           // $producto = TbProducto::with(['categoria','marca','tallas','colores','fichatecnica','Images'])->find($id);
           $producto = DB::select('SELECT * FROM vw_producto_compra WHERE cod_compra = ?',[$id]);
            //is_null()
            if(count($producto) == 0){
                return response()->json([
                    'error'=> true,
                    'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                    'status'=> 404,'data'=> null],404);
            }

            $colores = DB::select('SELECT * FROM tb_detalle_color_productos WHERE cod_compra = ?',[$id]);
            $tallas = DB::select('SELECT * FROM tb_detalle_talla_productos WHERE cod_compra = ?',[$id]);
            $ficha = DB::select('SELECT * FROM tb_ficha_tecnica_productos WHERE cod_compra = ?',[$id]);
            if(count($ficha) > 0){
                $ficha = $ficha[0];
            }else{
                $ficha = null;
            }
            return response()->json([
                'error'=> false,'message'=> 'Registro encontrado','status'=> 200,
                'data'=> $producto[0],'colores'=> $colores,'tallas'=>$tallas,'ficha' => $ficha
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }

    }


    public function update(Request $request, $id)
    {
        /*:::::::::::::::: INICIAMOS LA TRANSACCION ::::::::::::::*/
        DB::beginTransaction();
        /* ::::::::::::::: ENGLOBAMOS TODO EN UN TRY ::::::::::::: */
        try {
            /* :::::::: ELIMINAMOS COLORE - TALLAS - FICHA PARA VOLVER A REGISTRAR :::::::: */
            TbDetalleTallaProducto::where('cod_compra',$id)->delete();
            TbDetalleColorProducto::where('cod_compra',$id)->delete();
            TbFichaTecnicaProducto::where('cod_compra',$id)->delete();

            $newProducto = json_decode($request->producto);

            /* ::::::::::::::: ACTULIZAR DATOS DE LA COMPRA ::::::: */
            $compra = TbCompraProducto::find($id);
            /* ************************************************** */
                $code_producto = $compra->cod_producto;
            /* ************************************************** */
            $compra->nombre_producto = $newProducto->titulo_producto . ' ' . $newProducto->descripcion_corta;
            $compra->stock_ingreso = $newProducto->stock;
            $compra->stock_egreso = $newProducto->stock_egreso;
            $compra->precio_compra = $newProducto->precio_compra;
            $compra->precio_venta_unidad = $newProducto->precio_unitario;
            $compra->precio_venta_cantidad = $newProducto->precio_mayor;
            $compra->cantidad_venta_mayor = $newProducto->cantidad_venta_mayor;
            $compra->numero_lote = $newProducto->numero_lote;
            $compra->fecha_compra = $newProducto->fecha_compra;
            $compra->fecha_elaboracion = $newProducto->fecha_elaboracion;
            $compra->fecha_caduca = $newProducto->fecha_caduca;
            $compra->cod_producto = $code_producto;
            $compra->estado =  $newProducto->estado;
            $compra->save();


            /* :::::::: ACTUALIZAR DATOS DEL PRODUCTO :::::::::::: */
            $producto = TbProducto::find($code_producto);
            $producto->code_barra= $newProducto->code_barra;
            $producto->nombre_producto= $newProducto->titulo_producto;
            $producto->descripcion_corta= $newProducto->descripcion_corta;
            $producto->descripcion_larga= '';
            $producto->cod_categoria= $newProducto->cod_categoria;
            $producto->cod_marca= $newProducto->cod_marca;
            $producto->cod_sucursal = 1;
            $producto->estado= 1;
            $producto->save();

            /* :::::::::: PROCEDEMOS A REGISTAR LAS TALLAS ::::::::::: */
            foreach (json_decode($request->tallas) as $value) {
              $modelTalla = new TbDetalleTallaProducto();
              $modelTalla->cantidad = $value->cantidad;
              $modelTalla->cod_compra = $id;
              $modelTalla->talla = $value->talla;
              $modelTalla->save();
            }
            /* :::::::: PROCEDEMOS A REGISTRAR LOS COLORES ::::::: */
            foreach (json_decode($request->colores) as $value) {
                $modelColor = new TbDetalleColorProducto();
                $modelColor->color = $value->color;
                $modelColor->cantidad = $value->cantidad;
                $modelColor->cod_compra = $id;
                $modelColor->save();
            }
            /* :::::::::: PROCEDEMOS A REGISTRAR LA FICHA TECNICA ::::::: */
            $rqficha = json_decode($request->fichaproducto);
            $ficha = new TbFichaTecnicaProducto();
            $ficha->body_ficha = $rqficha->body_ficha;
            $ficha->cod_compra = $id;
            $ficha->save();

            /* :::::::::: PROCEDEMOS A REGISTRAR LAS IMAGENES ::::::::::: */
            $i = 0;
            if($request->hasFile('images')){
                foreach ($request->file('images') as $image) {
                    $modelimage = new TbImagenProducto();
                    $nombre_imagen = $image->getClientOriginalName();
                    $destinationPath = "public/images/catalogo";
                    Storage::putFileAs($destinationPath, $image, 'pd'.$nombre_imagen);
                    $modelimage->image_name = 'pd'.$nombre_imagen;
                    $modelimage->orden =$i;
                    $modelimage->cod_producto = $code_producto;
                    $modelimage->save();
                    $i++;
                }
            }
            /* :::: LANZAMOS EL COMMIT :::::: */
            DB::commit();
            /* :::: RETORNAMOS LA RESPUESTA :::::: */
            return response()->json([
                'error'=> false,'message'=> 'Registro actualizado correctamente',
                'status'=> 200,'data' => null
           ],200);

        } catch (\Throwable $e) {
            /* :::::::::::: REVERTIMOS TODO EN CASO DE UN ERROR::::: */
            DB::rollBack();
            /* :::::::::::: NOTIFICAR AL USUARIO EL ERROR :::::::::: */
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function destroy($id)
    {
        $producto = TbProducto::find($id);
        $producto->delete();

        return response()->json([
            'error'=> false,'message'=> 'Registro eliminado correctamente','status'=> 200,'data'=> $producto
        ],200);
    }

    public function buscarProductoCodigoBarra($codbarra)
    {
        $producto = new TbProducto();
        $producto = TbProducto::with(['categoria','marca'])
            ->where('estado',1)
            ->where('code_barra',$codbarra)
        ->get();

        if(count($producto) > 0){
            return response()->json([
                'data'=> $producto[0]
            ],200);
        }

        return response()->json([
            'message'=> 'Producto no encontrado en nuestros registros.'
        ],404);
    }

    public function addproductoCodigoBarra($codbarra){
        try {
            $producto = DB::select('SELECT * FROM vw_kardex where code_barra = "'.$codbarra.'" ');
            if(count($producto) > 0){
                return response()->json([
                    'data'=> $producto[0]
                ],200);
            }

            return response()->json([
                'message'=> 'Este producto ya no se encuentra disponible.'
            ],404);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function otherProductoFlask(Request $request)
    {
        /*:::::::::::::::: INICIAMOS LA TRANSACCION ::::::::::::::*/
        DB::beginTransaction();
        /* ::::::::::::::: ENGLOBAMOS TODO EN UN TRY ::::::::::::: */
        try {
            /* :::::::: GUARDAMOS DATOS DEL PRODUCTO :::::::::::: */

            /* ************************************************* */
                $code_producto = '';
            /* ************************************************* */

            $producto = TbProducto::create([
                'code_barra' => 'COD-000000',
                'nombre_producto' => $request->nombre_producto,
                'descripcion_corta' => '',
                'descripcion_larga' => '',
                'cod_categoria' => 1,
                'cod_marca' => 1,
                'cod_sucursal' => 1,
                'estado' => 3
            ]);

            $producto->refresh();

            /* ************************************************ */
                $code_producto = $producto->cod_producto;
            /* ************************************************ */

            /* REGISTRAMOS LA NUEVA COMPRA */
            $newcompra = TbCompraProducto::create([
                'nombre_producto' => $request->nombre_producto,
                'stock_ingreso' => $request->cantidad,
                'stock_egreso' => 100,
                'precio_compra' => $request->precio,
                'precio_venta_unidad' =>$request->precio,
                'precio_venta_cantidad' => $request->precio,
                'cantidad_venta_mayor' => 100,
                'numero_lote' => 'LTE-000000',
                'fecha_compra' => $request->fecha_compra,
                'fecha_elaboracion' => $request->fecha_compra,
                'fecha_caduca' => $request->fecha_compra,
                'cod_producto'=> $code_producto,
                'estado' =>1
            ]);

            /* :::: LANZAMOS EL COMMIT :::::: */
            DB::commit();
            /* :::: RETORNAMOS LA RESPUESTA :::::: */
            return response()->json([
                'error'=> false,'message'=> 'Producto registrado correctamente',
                'status'=> 200,'data' => $newcompra->refresh(),'cod_producto' => $code_producto
           ],200);

        } catch (\Throwable $th) {
            /* :::::::::::: REVERTIMOS TODO EN CASO DE UN ERROR::::: */
            DB::rollBack();
            /* :::::::::::: NOTIFICAR AL USUARIO EL ERROR :::::::::: */
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }
    }
}
