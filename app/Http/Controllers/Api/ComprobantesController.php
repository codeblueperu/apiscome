<?php

namespace App\Http\Controllers\Api;

use App;
use App\Http\Controllers\Controller;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ComprobantesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    /* ******************* TICKET DE VENTA ******************** */
    public function generarTikenVenta($codventa){

        $data = DB::select('SELECT * FROM vw_ventas_detalles where cod_venta = ?', [$codventa]);

        $tbody = "";
        foreach($data as $item){
            $tbody .= "<tr>
                    <td>".$item->cantidad."</td>
                    <td>".strtoupper($item->producto)."</td>
                    <td>".$item->precio_venta."</td>
                    <td>".$item->subtotal_producto."</td>
            </tr>";
        }

        $charreo = "";

        if($data[0]->numero_cupon != ''){
            $charreo = '<div class="div-charreo">
                <p>Estimado usuario usted fue beneficiado de un descuento por promocion de <b>S/'.$data[0]->monto_descuento.'</b>, utilizando el codigo de cupon <b>'.$data[0]->numero_cupon.'</b></p>
                </div>';
        }

        PDF::setOption(['dpi' => 150, 'defaultFont' => 'Courier']);
        $pdf = App::make('dompdf.wrapper');

        $html = '<style>
                    @page{
                        margin-left: 0.3cm;
                        margin-right: 0.3cm;
                        margin-top: 0;
                        font-family: "Courier";
                    }
                    '.$this->styleCabeceraB7(7,9).'
                    .div-data-comp{
                        position: absolute;
                        top: 45px;
                        width:100%;
                        font-size:8px;
                    }
                    .div-data-comp h5{text-align:center;font-size:9px;}
                    .div-data-comp .num-ticket {position: relative;top:-15px;font-size:9px;text-align:center}
                    .div-data-comp .fecha{width:100% !important;position: relative;top:-20px;}
                    .div-data-comp .cajero{width:100% !important;position: relative;top:-18px;}
                    .div-data-comp .tpago{width:100% !important;position: relative;top:-15px;}
                    .div-data-client{
                        position: absolute;
                        top: 115px;
                        width:100%;
                        font-size:8px;
                        border-bottom:1px dashed #62656B;
                        padding-bottom:7px !important;
                    }
                    .div-data-client .sucursal{width:100% !important;position: relative;top:2px;}
                    .div-detalle{
                        position: absolute;
                        top: 140px;
                        width:100%;
                        font-size:8px;
                    }
                    .div-detalle table {width:100% !important}
                    .div-detalle table thead {width:100% !important;border-bottom:1px dashed #62656B;}
                    .div-detalle table tbody {width:100% !important;border-bottom:1px dashed #62656B; font-size:7px;}
                    table tbody tr td{padding-top:5px !important;}
                    .message{
                        width:100%;
                        position:absolute;
                        bottom:0;
                        font-size:7px;
                        text-align:center;
                    }
                    .div-charreo{
                        width:100%;
                        text-align:center;
                    }
                </style>
                <body>
                    '.$this->cabeceraCombrobanteB7($data[0]->cod_sucursal).'
                    <div class="div-data-comp">
                        <h5>TICKET DE VENTA</h5>
                        <p class="num-ticket">'.$data[0]->numero_comprobante.'</p>
                        <div class="fecha"><span style="display:inline-block;width:30% !important">FECHA DE EMISION </span><span>: '.$data[0]->fecha_venta.'</span></div>
                        <div class="cajero"><span style="display:inline-block;width:30% !important">CLIENTE</span><span>: '.$data[0]->cliente.'</span></div>
                        <div class="tpago"><span style="display:inline-block;width:30% !important">CONDICION DE PAGO</span><span>: '.$data[0]->estado_pago.'</span></div>
                    </div>
                    <div class="div-data-client">
                        <div class="cliente"><span style="display:inline-block;width:30% !important">SUCURSAL </span><span>: '.$data[0]->sucursal.'</span></div>
                        <div class="sucursal"><span style="display:inline-block;width:30% !important">CAJERO(A)</span><span>: '.$data[0]->cajero.'</span></div>
                    </div>
                    <div class="div-detalle">
                        <table>
                            <thead>
                                <tr>
                                    <th>CAN.</th>
                                    <th>DESCRIPCION</th>
                                    <th>P. UNT.</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                '.$tbody.'
                            </tbody>
                        </table>
                            <br><br>
                        <div class="sucursal"><span style="display:inline-block;width:80% !important; text-align:right;">OP. GRAVADA</span><span>: S/'.number_format(($data[0]->monto_subtotal + $data[0]->monto_igv),2).'</span></div>
                        <div class="sucursal"><span style="display:inline-block;width:80% !important; text-align:right;">DESCUENTO</span><span>: S/-'.$data[0]->monto_descuento.'</span></div>
                        <div class="sucursal"><span style="display:inline-block;width:80% !important; text-align:right;">IMPORTE TOTAL</span><span>: S/'.$data[0]->monto_total.'</span></div>
                        '.$charreo.'
                    </div>
                    <div class="message">***** Gracias por tu Preferencia *****</div>
                </body>';

        $pdf->loadHTML(utf8_decode($html));

        $pdf->setPaper("b7", 'portrait');
        return $pdf->stream('dompdf.pdf');
    }

    /* *******************  CABECERA B7 *********************** */
    public function cabeceraCombrobanteB7($cod_sucursal){
        $sucursal = DB::select('SELECT * FROM tb_sucursals where cod_sucursal = ?', [$cod_sucursal]);
        $header = '<div class="div-header">
                        <h5>'.$sucursal[0]->nombre_sucursal.'</h5>
                        <p class="p-ruc">R.U.C '.$sucursal[0]->ruc.'</p>
                        <p class="p-add">Dom. Fiscal: '.$sucursal[0]->direccion.'</p>
                        <p class="p-cel">Telefono(s): '.$sucursal[0]->telefono.'</p>
                        <p class="p-email">Email: '.$sucursal[0]->email.'</p>
                    </div>';

        return $header;
    }

    /***************** ESTILOS CABECERA B7 ********************** */
    public function styleCabeceraB7($sizeheader,$sizetitle){
        $styleB7 = '
            .div-header{
                width:100%;
                font-size:'.$sizeheader.'px;
                text-align:center;
                font-family: "Courier";
            }
            .div-header h5{
                padding-bottom:0px;
                font-size:'.$sizetitle.'px;
            }
            .div-header .p-ruc{
                position: relative;
                top: -15px;
            }
            .div-header .p-add{
                position: relative;
                top: -23px;
            }
            .div-header .p-cel{
                position: relative;
                top: -31px;
            }
            .div-header .p-email{
                position: relative;
                top: -39px;
            }
        ';
        return $styleB7;
    }

    public function pruebaScript($codcaja){
        try {
            /* CUADRE CAJA DATOS */
        $datacajacuadre = DB::select('SELECT * FROM dbscome.vw_cuadrar_caja_chica where cod_caja = ?', [$codcaja]);

        /* DETALLE MOVIMIENTO VENTAS */
        $detalleventas = DB::select('SELECT date_format(fecha_venta, "%d/%m/%Y - %H:%i:%s %p") as fecha, cliente as concepto,numero_comprobante as codigo ,
        monto_total as ingreso, 0 as salida FROM dbscome.vw_ventas where
        date_format(fecha_venta, "%Y-%m-%d %H:%i") BETWEEN date_format("2022-11-14 10:16:00", "%Y-%m-%d %H:%i") AND
        F_RETORNA_FECHA_CIERRE(1) ');

            return response()->json([
                'data'=> $datacajacuadre
            ],200);

        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }


    }

    /* ******************** REPORTE CUADRE DE CAJA **************** */
    public function reportecuadreCaja($codcaja){
        PDF::setOption(['dpi' => 150, 'defaultFont' => 'Courier']);
        $pdf = App::make('dompdf.wrapper');

        /* DETALLE TIPO DE PAGO */
        $datatipopago = DB::select('select  pg.descripcion_corta,sum(dt.monto) as monto_total from tb_detalle_pagos dt, tb_ventas vt,
            tb_tipo_pagos pg, tb_cajas cj, users u where dt.cod_venta = vt.cod_venta and dt.cod_tipo_pago = pg.cod_tipo_pago
            and vt.estado_venta = 1 and vt.estado_pago = 1 and vt.cod_usuario = cj.cod_usuario and
            date_format(vt.fecha_venta, "%Y-%m-%d %H:%i") BETWEEN date_format(cj.fecha_apertura, "%Y-%m-%d %H:%i") AND
            F_RETORNA_FECHA_CIERRE(cj.cod_caja) and cj.cod_caja = ? group by descripcion_corta', [$codcaja]);

        /* CUADRE CAJA DATOS */
        $datacajacuadre = DB::select('SELECT * FROM dbscome.vw_cuadrar_caja_chica where cod_caja = ?', [$codcaja]);

        /* DETALLE MOVIMIENTO VENTAS */
        $detalleventas = DB::select('SELECT date_format(fecha_venta, "%d/%m/%Y - %H:%i") as fecha, cliente as concepto,numero_comprobante as codigo ,
        monto_total as ingreso, 0 as salida FROM vw_ventas where date_format(fecha_venta, "%Y-%m-%d %H:%i") BETWEEN date_format("'.$datacajacuadre[0]->fecha_apertura.'", "%Y-%m-%d %H:%i") AND
        F_RETORNA_FECHA_CIERRE('.$datacajacuadre[0]->cod_caja.') and suestadoventa = "1" and suestadopago = "1"   UNION SELECT date_format(gt.fecha_registro, "%d/%m/%Y - %H:%i") as fecha,gt.descripcion_gasto as concepto,"-" as codigo,0 as ingreso, gt.monto_gasto as salida FROM tb_gastos gt
        where date_format(gt.fecha_registro, "%Y-%m-%d %H:%i")
        between date_format("'.$datacajacuadre[0]->fecha_apertura.'", "%Y-%m-%d %H:%i") AND F_RETORNA_FECHA_CIERRE('.$datacajacuadre[0]->cod_caja.') and gt.cod_sucursal = 1
         UNION SELECT date_format(vt.fecha_venta, "%d/%m/%Y - %H:%i") as fecha,ig.descripcion_ingreso as concepto, "-" as codigo,
        ig.monto_ingreso as ingreso, 0 as salida
        FROM tb_detalle_acreditos cr, tb_ventas vt,tb_ingreso_cajas ig where cr.cod_venta = vt.cod_venta and
        ig.cod_venta = vt.cod_venta and date_format(vt.fecha_venta, "%Y-%m-%d %H:%i")
        between date_format("'.$datacajacuadre[0]->fecha_apertura.'", "%Y-%m-%d %H:%i") AND F_RETORNA_FECHA_CIERRE('.$datacajacuadre[0]->cod_caja.') and ig.cod_sucursal = 1
        ');

        /* CREACION DE TABLAS */
        $i = 1;
        $totalgeneral = 0;

        /* SEGUN MONTO INICIAL */
        $tr_monto_init = '<tr><td>'.$i.'</td><td>APERTURA</td><td>S/ '.( is_null($datacajacuadre[0]->monto_inicial) ? number_format(0,2) : number_format($datacajacuadre[0]->monto_inicial,2)).'</td></tr>';
        $table_inicial = $this->crearTable(['#','DESCRIPCION','MONTO'], $tr_monto_init,'60%');
        $totalgeneral +=  $datacajacuadre[0]->monto_inicial;
        $i++;

        /* SEGUN TIPO DE PAGO */
        $tr_tipopago = '';
        foreach($datatipopago as $item){
            $tr_tipopago .= '<tr>
                <td>'.$i.'</td>
                <td>'.strtoupper($item->descripcion_corta).'</td>
                <td>S/ '.$item->monto_total.'</td>
            </tr>';
            $totalgeneral +=  $item->monto_total;
            $i++;
        }
        $table_tipo_pago = $this->crearTable(['#','DESCRIPCION','MONTO'], $tr_tipopago,'60%');

        /* SEGUN MONTO INGRESO */
        $tr_table_ingreso = '';
        foreach($datacajacuadre as $item){
            $ingreso = 0;
            if(!is_null($item->monto_ingreso)){
                $ingreso = $item->monto_ingreso;
                $totalgeneral +=  $item->monto_ingreso;
            }

            $tr_table_ingreso .= '<tr>
                <td>'.$i.'</td>
                <td>INGRESOS</td>
                <td>S/ '.number_format( $ingreso,2).'</td>
            </tr>';
            $i++;
        }
        $table_ingresos = $this->crearTable(['#','DESCRIPCION','MONTO'], $tr_table_ingreso,'60%');

        /* MONTO GASTOS */
        $tr_monto_gasto = '<tr><td>'.$i.'</td><td>MIS GASTOS</td><td style="color:red; font-weight: bold;">- S/ '.( is_null($datacajacuadre[0]->monto_gasto) ? number_format(0,2) : number_format($datacajacuadre[0]->monto_gasto,2)).'</td></tr>';
        $table_gasto = $this->crearTable(['#','DESCRIPCION','MONTO'], $tr_monto_gasto,'60%');
        $totalgeneral -=  $datacajacuadre[0]->monto_gasto;
        $i++;

        /* DETALLE MOVIMIENTOS */
        $tr_detalle = '';
        foreach($detalleventas as $item){
            $tr_detalle .= '<tr>
                <td>'.$item->fecha.'</td>
                <td>'.strtoupper($item->concepto). ' '. $item->codigo .'</td>
                <td>'.($item->ingreso == 0.00 ? '' : $item->ingreso ).'</td>
                <td>'.($item->salida == 0.00 ? '' : $item->salida).'</td>
            </tr>';
        }
        $tr_detalle .='<tr><td colspan="2" style="padding:9px;background:#FFEFE7; color:#E33D00; font-weight:bold;">TOTAL GENERAL</td><td style="font-size:25px;color:#E33D00;">'.number_format($totalgeneral,2).'</td><td style="font-size:25px;color:#E33D00;">'.$datacajacuadre[0]->monto_gasto.'</td></tr>';
        $table_detalle = $this->crearTable(['FECHA','CONCEPTO','ENTRADAS','SALIDAS'],$tr_detalle,'100%');

        $html = '<style>
                    @page{
                        margin-left: 0.9cm;
                        margin-right: 0.9cm;
                        margin-top: 0;
                        font-family: "Courier";
                    }
                    .cabecera_titulo{
                        background: #E33D00;
                        color:#FFF;
                        width:100%;
                        height:40px;
                        text-align:left;
                    }
                    .cabecera_titulo > h3{
                       padding:9px;
                       font-size:18px;
                       margin-left:25px
                    }

                    .subtitle{
                        display:block;
                        color:#666666;
                        font-size:13px;
                    }

                    #customers {
                        font-family: Arial, Helvetica, sans-serif;
                        border-collapse: collapse;
                      }

                      #customers td, #customers th {
                        border: 1px solid #ddd;
                        padding: 5px;
                        font-size:11px;
                      }

                      #customers tr:nth-child(even){background-color: #f2f2f2;}

                      #customers tr:hover {background-color: #ddd;}

                      #customers th {
                        padding-top: 5px;
                        padding-bottom: 5px;
                        text-align: center;
                        background-color: #FFEFE7;
                        color: #E33D00;
                        font-size:12px;
                      }
                      .sub_title{
                        width:100%;
                        display:block;
                      }

                </style>
                <body>

                    <div class="cabecera_titulo"><h3>ARQUEO DE CAJA DIARIO</h3></div>
                    <div class="sub_title">
                      <div style="display:block;width:100% !important;"><span style="display: inline-block;width:20% !important;">Sucursal</span> <span>: '.$datacajacuadre[0]->sucursal.'</span></div>
                      <div style="display:block;width:100% !important;"><span style="display: inline-block;width:20% !important;">Usuario</span> <span>: '.$datacajacuadre[0]->usuario.'</span></div>
                      <div style="display:block;width:100% !important;"><span style="display: inline-block;width:20% !important;">Fecha Apertura</span> <span>: '.date('d/m/Y H:i:s',strtotime($datacajacuadre[0]->fecha_apertura)).'</span></div>
                      <div style="display:block;width:100% !important;"><span style="display: inline-block;width:21% !important;">Fecha Cierre </span><span>: '.(is_null($datacajacuadre[0]->fecha_cierre) ? '' : date('d/m/Y H:i:s',strtotime($datacajacuadre[0]->fecha_cierre))).'</span></div>
                    </div>
                    <h3 class="subtitle">APERTURA DE CAJA:</h3>
                    '.$table_inicial.'
                    <h3 class="subtitle">SEGUN TIPO DE PAGO:</h3>
                    '.$table_tipo_pago.'
                    <h3 class="subtitle">MIS INGRESOS:</h3>
                    '.$table_ingresos.'
                    <h3 class="subtitle">GASTOS:</h3>
                    '.$table_gasto.'

                    <div style="display:block;width:58% !important;padding:9px;background:#FFEFE7; color:#E33D00; font-weight:bold;"><span style="display: inline-block;width:63% !important;">MONTO FINAL</span> <span style="font-size:25px;">S/ '.number_format($totalgeneral, 2).'</span></div>
                    <br><br>
                    <h3 class="subtitle">DETALLE(S):</h3>
                    '.$table_detalle.'

                </body>';

        $pdf->loadHTML(utf8_decode($html));

        $pdf->setPaper("A4", 'portrait');
        return $pdf->stream('dompdf.pdf');

    }

    public function crearTable($array,$cuerpo,$size){

        $columns = '';
        foreach($array as $item){
            $columns .= ' <th>
                '. $item .'
            </th>';
        }
        $table = '<table style="width: '.$size.';" id="customers">
        <thead>
            <tr>
                '.$columns.'
            </tr>
            </thead>
            <tbody>'.
            $cuerpo
            .'</tbody>
        </table>';

        return $table;
    }
}

