<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbCliente;
use App\Models\TbCompraProducto;
use App\Models\TbCorrelativo;
use App\Models\TbCredito;
use App\Models\TbDetalleAcredito;
use App\Models\TbGasto;
use App\Models\TbIngresoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TbVenta;
use App\Models\TbDetalleVenta;
use App\Models\TbProducto;
use App\Models\TbDetalleTallaProducto;
use Illuminate\Support\Facades\Auth;
use App\Models\TbDetallePago;
use App\Models\TbDescuentoVenta;

class VentaProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index()
    {
        //
    }

    /* METODO DE VENTA  */
    public function store(Request $request)
    {
        /*:::::::::::::::: INICIAMOS LA TRANSACCION ::::::::::::::*/
        DB::beginTransaction();
        $user = Auth::user();
        /* ::::::::::::::: ENGLOBAMOS TODO EN UN TRY ::::::::::::: */
        try {
            /* ALISTAMOS EL COMPROBANTE NUMERO DE CORRELATIVO */
            $correlativo = DB::select('SELECT cod_correlativo,abreviatura,correlativo, F_CORRELATIVO(cod_tipo_comprobante)as numerocorrelativo,cod_tipo_comprobante FROM dbscome.tb_correlativos where cod_tipo_comprobante = ?', [1]);

            /* ACTUALIZAMOS EL NUMERO DEL CORRELATIVO */
                TbCorrelativo::where('cod_correlativo',$correlativo[0]->cod_correlativo)
                ->update(array('correlativo' => ($correlativo[0]->correlativo + 1) ));
            /* PRIMERO REGISTRAMOS LA VENTA */
            $dtventa = json_decode($request->venta);
            $venta = TbVenta::create([
                'numero_comprobante' => $correlativo[0]->numerocorrelativo,
                'numero_cupon' => $dtventa->numero_cupon,
                'fecha_venta' => date('Y-m-d H:i:s',strtotime($dtventa->fecha_venta)),
                'monto_subtotal' => $dtventa->monto_subtotal,
                'monto_igv' => $dtventa->monto_igv,
                'monto_descuento' => $dtventa->monto_descuento,
                'monto_total' => $dtventa->monto_total,
                'estado_venta' => 1,
                'estado_pago' => $dtventa->estado_pago,
                'cod_usuario' => $user->id,
                'cod_tipo_comprobante' => $dtventa->cod_tipo_comprobante,
                'cod_cliente' => $dtventa->cod_cliente,
                'cod_sucursal' => 1
            ]);

            /************************************************************************************ */
                $cod_venta = $venta->cod_venta;
                $monto_total_venta = $venta->monto_total;
                $fecha_venta_general = date('Y-m-d H:i:s',strtotime($dtventa->fecha_venta));
            /*********************************************************************************** */

            /* REGISTRO DETALLE DE VENTA */
            $dtdetalle = json_decode($request->detalle);
            $rpt = '';
            foreach($dtdetalle as $item){

              /* OBTENER TODO LOS PRODUCTOS CON STOCK DISPONIBLE */
                $dtproduct = TbCompraProducto::where('cod_producto',$item->cod_producto)
                ->where('estado',1)->get();
                /* OBTENER STOCK GENERAL */
                $stock_almacen = 0;
                foreach ($dtproduct as $key) {
                    $stock_almacen += $key->stock_egreso;
                }

               if($stock_almacen < $item->cantidad){
                    DB::rollBack();
                    return response()->json(['message'=> 'El STOCK del producto <b>'.$dtproduct[0]->nombre_producto .'</b> es menor a la cantidad a vender. Tu STOCK es de: '.$dtproduct[0]->stock_egreso.' UND'],409);
                }

                TbDetalleVenta::create([
                    'presentacion' => $item->presentacion,
                    'color' => $item->color,
                    'precio_venta' => $item->precio_venta,
                    'cantidad' => $item->cantidad,
                    'descuento' => $item->descuento,
                    'total' => $item->total,
                    'cod_compra' => $dtproduct[0]->cod_compra,
                    'cod_venta' => $cod_venta
                ]);

                $cantidad = $item->cantidad; //26

                foreach ($dtproduct as $valor) {

                    if($cantidad == 0){
                        break;
                    }
                    /* DESCONTAMOS EL STOCK DEL PRODUCTO Y DETALLE TALLA Y COLOR */
                   $newdtcompra = TbCompraProducto::find($valor->cod_compra);
                  // $descontar = 0;// $valor->stock_egreso - $cantidad;
                    $rpt .= $newdtcompra->stock_egreso . ' estock P | ';
                   if($cantidad > $newdtcompra->stock_egreso){
                       // $descontar = $newdtcompra->stock_egreso;

                        //$rpt .= '  ==== IF ===='. $descontar . ' | ';
                        TbCompraProducto::where('cod_compra', $valor->cod_compra)
                        ->update(array('stock_egreso' => ($valor->stock_egreso - $newdtcompra->stock_egreso)));

                        $cantidad -= $newdtcompra->stock_egreso;
                   }else{
                    //$descontar = $valor->stock_egreso - $cantidad;

                    //$rpt .= '  ==== ELSE ===='. $descontar . ' | ';
                    TbCompraProducto::where('cod_compra', $valor->cod_compra)
                    ->update(array('stock_egreso' => ($valor->stock_egreso - $cantidad)));
                    $cantidad -=  $cantidad;
                   }

                   $newdtcompra->refresh();
                   if($newdtcompra->stock_egreso == 0 ){
                        TbCompraProducto::where('cod_compra', $valor->cod_compra)->update(array('estado' => 0));
                    }

                    /* TbCompraProducto::where('cod_compra', $valor->cod_compra)
                    ->update(array('stock_egreso' => ($valor->stock_egreso - $cantidad))); */
                    /* ACTUALIZAR ESTADO EN CASO QUE SE AGOTE EL STOCK */
                   /*  $newdtcompra->refresh();

                     */

                    //$cantidad -= $descontar;
                    //$rpt .= '  ==== -- ===='. $cantidad. ' | ';
                }


            }
            /* END DETALLE DE VENTA */

            /* DETALLE DE PAGO */
            $dtpago = json_decode($request->detallepago);
            foreach($dtpago as $el){

                $insertpago = TbDetallePago::create([
                    'codigo_operacion' => $el->codigo_operacion,
                    'monto' => $el->monto,
                    'cod_tipo_pago' => $el->cod_tipo_pago,
                    'cod_venta' => $cod_venta
                ]);

                if(!$insertpago){
                    DB::rollBack();
                    return response()->json(['message'=> 'Error al guardar el detalle de pago.'],409);
                }
            }

            /* DETALLE PAGO A CREDITO */
            $dtcreditopago = json_decode($request->detallepagocredito);
            foreach($dtcreditopago as $item){
                /* BUSCAMOS AL CLIENTE PRIMERO  */
                $datacliente = TbCliente::find($dtventa->cod_cliente);
                /* VALIDAMOS SI EL CLIENTE CUNETA CON UN CREDITO APROBADO */
                $clientecredito = TbCredito::where('cod_cliente',$dtventa->cod_cliente)->get();

                if(count($clientecredito) == 0){
                    /* AGREGAR CREDITO */
                    TbCredito::create([
                        'monto_credito' => 300,
                        'monto_deuda' => 0,
                        'estado' => 1,
                        'cod_cliente' => $dtventa->cod_cliente,
                        'cod_sucursal' => 1
                    ]);
                    //VOLVEMOS A BUSCAR AL CLIENTE
                    $clientecredito = TbCredito::where('cod_cliente',$dtventa->cod_cliente)->get();
                    /* END AGREGAR  */

                   // DB::rollBack();
                   // return response()->json(['message'=> "El Sñr(a) <b>" .  $datacliente->apellidos_nombres . "</b>, no cuenta con un credito aprobado."],409);
                }
                /* CALCULAMOS SU NUEVA DEUDA */
                $deudaanterior = $clientecredito[0]->monto_deuda;
                $newdeuda = $deudaanterior + $item->montodebe;

                /* VALIDAMOS QUE SU DEUDA NO SEA MAYOR AL CREDITO OTORGADO */
                if($newdeuda > $clientecredito[0]->monto_credito){
                    DB::rollBack();
                    return response()->json(['message'=> 'El cliente esta superando su linea de credito, su credito es de <b>'. $clientecredito[0]->monto_credito .'</b> y su deuda actual es de <b>'. $deudaanterior .'</b>'],409);
                }

                /* GUARDAMOS O ACTUALIZAMOS  SU NUEVA DEUDA */
                TbCredito::where('cod_cliente', $dtventa->cod_cliente)
                ->update(array('monto_deuda' =>  $newdeuda));

                /* GUARDAMOS EL DETALLE DE SU COMPRA QUE DEBE */
                TbDetalleAcredito::create([
                    'cod_venta' => $cod_venta,
                    'cod_deuda' => $clientecredito[0]->cod_deuda ,
                    'monto_adelanto' => $item->montoadelanto,
                    'monto_debe' => ($monto_total_venta - $item->montoadelanto),
                    'total_deuda' => $monto_total_venta,
                    'estado' => 1
                ]);

                /* GAURDAMOS SU INGRESO PARA EL CUADRE DE CAJA */
                if($item->montoadelanto > 0){
                    TbIngresoCaja::create([
                        'descripcion_ingreso' => "El Sñr(a) " .  $datacliente->apellidos_nombres . ", realizo un adelanto de su compra.",
                        'monto_ingreso' => $item->montoadelanto,
                        'fecha_ingreso' => $fecha_venta_general,
                        'cod_sucursal' => 1,
                        'cod_venta' => $cod_venta
                    ]);
                }
            }

            /* DESCONTAMOS EL STOCK CUPON */
            if(!is_null($dtventa->numero_cupon)){
                $dtcupon = TbDescuentoVenta::where('numero_cupon',$dtventa->numero_cupon)->where('estado','1')->get();
                if(count($dtcupon) > 0){
                    $datos = TbDescuentoVenta::find($dtcupon[0]->cod_descuento);
                    $datos->stock = ($datos->stock - 1);
                    $datos->update();
                    $datos->refresh();
                    if($datos->stock == 0){
                        $datos->estado = 0;
                        $datos->update();
                    }
                }
            }

            /* :::: LANZAMOS EL COMMIT :::::: */
            DB::commit();
            /* :::: RETORNAMOS LA RESPUESTA :::::: */
            return response()->json(['error'=> false,'message'=> 'Su venta fue registrada con exito.','code' => $cod_venta, 'other' => $rpt],200);
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


    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function misventas($fechainicio,$fechafin){
        $sql = 'SELECT * FROM vw_ventas WHERE date_format(fecha_venta,"%Y-%m-%d") BETWEEN "'.$fechainicio.'" AND "'.$fechafin.'" ORDER BY date_format(fecha_venta,"%Y-%m-%d %H:%i") DESC';
        $data = DB::select($sql);
        if(count($data) > 0){
            return response()->json(['message' => 'Lista de ventas','data' => $data],200);
        }
        return response()->json(['message' => "no se encontro ninguna venta con los paramtros de busqueda."],404);
    }

    public function darBajaVenta($codventa)
    {
         /*:::::::::::::::: INICIAMOS LA TRANSACCION ::::::::::::::*/
         DB::beginTransaction();
         $user = Auth::user();
         /* ::::::::::::::: ENGLOBAMOS TODO EN UN TRY ::::::::::::: */
         try {
            /* BUSCAMOS LA VENTA */
            //$venta = TbVenta::find($codventa);
            /* ACTUALIZAMOS LA VENTA  */
            TbVenta::where('cod_venta',$codventa)
            ->update(array('estado_venta' => 0));
            /* BUSCAR DETALLE PARA ACTUALIZAR EL STOCK DEL PRODUCTO */
            $detalle = TbDetalleVenta::where('cod_venta',$codventa)->get();

            foreach ($detalle as $value) {
                /* BUSCAMOS EL PRODUCTO Y ACTUALIZAMOS */
                $producto = TbCompraProducto::find($value->cod_compra);
                $producto->stock_egreso = ($producto->stock_egreso + $value->cantidad);
                $producto->estado = '1';
                $producto->save();
            }

            /**DESACTIVAMOS EL DETALLE DEL CREDITO DE LA VENTA */
            $detallecredito = DB::select('SELECT * FROM tb_detalle_acreditos where cod_venta = ?', [$codventa]);
            if(count($detallecredito) > 0){
                TbDetalleAcredito::where('cod_venta',$codventa)
                ->update(array('estado' => 0));

                /* OBTENER MONTO QUE DEBIA */
                $montodebe = 0;
                $montodebe = $detallecredito[0]->monto_debe;

                /* ELIMINAR INGRESOS ABONADOS */
                DB::delete('DELETE FROM tb_ingreso_cajas where cod_venta = ?', [$codventa]);

                /* DESCONTAR DEUBA PRINCIPAL */
                DB::update("UPDATE tb_creditos SET monto_deuda = (monto_deuda - ? ) WHERE cod_deuda = ?",[$montodebe, $detallecredito[0]->cod_deuda]);
            }


            DB::commit();
            /* :::: RETORNAMOS LA RESPUESTA :::::: */
            return response()->json(['message'=> 'Su venta su dado de baja con exito.'],200);
        } catch (\Throwable $e) {
            /* :::::::::::: REVERTIMOS TODO EN CASO DE UN ERROR::::: */
            DB::rollBack();
            /* :::::::::::: NOTIFICAR AL USUARIO EL ERROR :::::::::: */
            throw $e;
        }

    }
}
