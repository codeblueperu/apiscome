<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CreditoResource;
use App\Http\Resources\VWDetalleClienteDeudaResource;
use App\Models\TbCliente;
use App\Models\TbCredito;
use App\Models\TbDetalleAcredito;
use App\Models\TbIngresoCaja;
use App\Models\TbVenta;
use App\Models\VWDetalleClienteDeuda;
use Illuminate\Http\Request;

class CreditoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }


    public function index()
    {
        return  CreditoResource::collection(TbCredito::with(['cliente'])->get());
    }


    public function store(Request $request)
    {
        return new CreditoResource(TbCredito::create($request->all()));
    }


    public function show($id)
    {
        return new CreditoResource(TbCredito::find($id));
    }

    public function update(Request $request, $id)
    {
        $credito = TbCredito::find($id);

        $credito->update($request->all());

        return new CreditoResource($credito->refresh());
    }

    public function destroy($id)
    {
        $credito = TbCredito::find($id);
        $credito->delete();
    }

    public function buscaDeudaCliente($codcredito){
        $data = VWDetalleClienteDeuda::where('cod_deuda',$codcredito)->get();

        return VWDetalleClienteDeudaResource::collection($data);
    }

    public function sumaradelantocliente(Request $request){
        $data = TbDetalleAcredito::find($request->coddetalle);

        if($request->montoadelanto > $data->monto_debe){
            return response()->json(['message'=> 'El monto de ingreso (<b>S/'.$request->montoadelanto.'</b>) no puede ser mayor al monto que debe(<b>S/'.$data->monto_debe.'</b>).'],409);
        }
        $monto = $data->monto_debe - $request->montoadelanto;

        /* REALIZAMOS LOS CALCULOS CORRESPONDIENTES */
        TbDetalleAcredito::where('cod_detalle', $request->coddetalle)
        ->update(array('monto_adelanto' => ($data->monto_adelanto +  $request->montoadelanto),
                'monto_debe' => $monto));

        /* ACTUALIZAMOS LA DATA */
        $data->refresh();

        //VALIDAMOS SI YA NO DEBE PARA DESACTIVAR LA VENTA QUE DEBE
        if($data->monto_debe == 0){
            /* ACTUALIZA DETALLE CREDITO */
            TbDetalleAcredito::where('cod_detalle', $request->coddetalle)
            ->update(array('estado' => 0));

            /* ACTUALIZA ESTADO PAGO VENTA */
            TbVenta::where('cod_venta',$request->codventa)
            ->update(array('estado_pago' => 2));
        }

        /* DESCONTAMOS DE LA DEUDA GENERAL */
        $dtcredito = TbCredito::find($request->codcredito);
        /* UPDATE */
        TbCredito::where('cod_deuda',$request->codcredito)
        ->update(array('monto_deuda' => ($dtcredito->monto_deuda - $request->montoadelanto)));

        /* REALIZAMOS UN REFRESH DE LA DEUDA GENERAL */
        $dtcredito->refresh();

        /* VALIDAMOS SI YA NO TIENE NINGUNA DEUDA PARA CAMBIAR EL ESTADO DE LA DEUDA GENERAL */
            if($dtcredito->monto_deuda == 0){
                TbCredito::where('cod_deuda',$request->codcredito)
                ->update(array('estado' => 1));
            }
        /* GUARDAMOS EL NUEVO INGRESO DE CAJA */
        $dtcliente = TbCliente::find($dtcredito->cod_cliente);
        TbIngresoCaja::create([
            'descripcion_ingreso' => "El Sñr(a) " .  $dtcliente->apellidos_nombres . ", realizo un pago de su deuda.",
            'monto_ingreso' => $request->montoadelanto,
            'fecha_ingreso' => date('Y-m-d H:i:s',strtotime($request->fecha_adelanto)),
            'cod_sucursal' => 1,
            'cod_venta' => $request->codventa
        ]);

        return response()->json([
           'message'=> 'Exelente, su ingreso fue registrado.'
        ],200);
    }

}
