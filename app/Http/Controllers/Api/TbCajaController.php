<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VwCajaResource;
use Illuminate\Http\Request;
use App\Http\Resources\CajaResource;
use App\Models\TbCaja;
use App\Http\Requests\Caja\StoreRequest;
use App\Http\Requests\Caja\UpdateRequest;
use PHPUnit\Framework\Constraint\IsNull;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\VWCuadreCaja;

class TbCajaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }
    public function datoscaja($fecha_apertura, $fecha_cierre)
    {
       // $sql = "SELECT * FROM tb_cajas where fecha_apertura between  ''";
        $caja = TbCaja::whereBetween('fecha_apertura',[$fecha_apertura, $fecha_cierre])
        ->where('cod_sucursal',1)
        ->get()->sortDesc();
        return CajaResource::collection($caja);
    }

    public function store(StoreRequest $request)
    {
        $user = Auth::user();
        //->whereBetween('fecha_apertura',[$request->fecha_apertura, $request->fecha_cierre])
        /* $open = TbCaja::where('cod_usuario',$user->id)
                        ->where('estado','APERTURADA')
                        ->whereDate('fecha_apertura',date('Y-m-d  H:i',strtotime($request->fecha_apertura)))
                        ->get(); */
        $sql = "SELECT * FROM dbscome.tb_cajas where date_format(fecha_apertura, '%Y-%m-%d %H:%i') >= '".$request->fecha_apertura."' or date_format(fecha_cierre, '%Y-%m-%d %H:%i') >='". $request->fecha_apertura ."' and cod_usuario = ". $user->id;
        $open = DB::select($sql);

        if($open){
            return response()->json([
                'error'=> true,'message'=> 'estimado usuario, ya existe una caja aperturada con la fecha <b>'.date('d/m/Y H:i:s',strtotime($request->fecha_apertura)).'</b>.',
                'status'=> 409,'data'=>$open
            ],409);
        }

        TbCaja::create([
            'descripcion_caja' => $request->descripcion_caja,
            'fecha_apertura'=> date('Y-m-d H:i:s',strtotime($request->fecha_apertura)),
            'fecha_cierre'=>null,
            'monto_inicial'=>$request->monto_inicial,
            'monto_efectivo'=>null,
            'monto_digital'=>null,
            'monto_ingreso'=>null,
            'monto_gasto'=>null,
            'monto_total'=>null,
            'estado'=>'APERTURADA',
            'cod_sucursal'=> 1,
            'cod_usuario'=>$user->id

        ]);
        return response()->json([
            'error'=> false,'message'=> 'Muy bien! <b>'. $request->descripcion_caja .'</b>, tu caja fue APERTURADA con exito.',
            'status'=> 200
        ],200);
    }


    public function show($id)
    {
       // return CajaResource::collection(DB::table("VW_CUADRAR_CAJA_CHICA")->where('cod_caja',$id));
        return new VwCajaResource(VWCuadreCaja::find($id));
    }


    public function update(UpdateRequest $request, $id)
    {
        $caja = TbCaja::find($id);
        $caja->estado = "CERRADA";
        $caja->descripcion_caja = $request->descripcion_caja;
        $caja->fecha_cierre = $request->fecha_cierre;
        $caja->monto_efectivo = $request->monto_efectivo;
        $caja->monto_digital = $request->monto_digital;
        $caja->monto_ingreso = $request->monto_ingreso;
        $caja->monto_gasto = $request->monto_gasto;
        $caja->monto_total = $request->monto_total;
        $caja->save();

        return response()->json([
            'error'=> false,'message'=> 'Exelente! <b>'. $request->descripcion_caja .'</b>, su caja fue cerrada con exito.',
            'status'=> 200
        ],200);

    }


    public function destroy($id)
    {
        //NO
    }

    public function validaestadocaja($estatus)
    {
        $user = Auth::user();
        $caja = TbCaja::where('cod_usuario',$user->id)
        ->where('cod_sucursal', 1)
        ->where('estado','APERTURADA')->get();

        if($caja->count() == 0){
            return response()->json([
                'error'=> true,'message'=> 'Estimado usuario, antes de realizar una venta tiene que aperturar su caja',
                'status'=> 409
            ],409);
        }
    }
}
