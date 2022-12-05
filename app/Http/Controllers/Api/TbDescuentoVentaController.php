<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DescuentoVentaResource;
use App\Models\TbDescuentoVenta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TbDescuentoVentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function buscarcuponesporFecha($fechaInit,$fechaFin)
    {
        $cupon = TbDescuentoVenta::whereBetween('fecha_expiracion', [$fechaInit,$fechaFin])->get()->sortDesc();
        return DescuentoVentaResource::collection($cupon);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'numero_cupon' => 'required|max:12|unique:tb_descuento_ventas,numero_cupon',
            'monto_descuento'=> 'required|numeric',
            'fecha_expiracion'=> 'required|date',
            'stock'=> 'required|numeric',
            'estado'=> 'required'
        ]);

        if($validated->fails()){
            throw new HttpResponseException(response()->json([
                'errors'      => $validated->errors()
            ],409));
        }

        $user = Auth::user();
        $datos = TbDescuentoVenta::create([
            'numero_cupon' => strtoupper($request->numero_cupon),
            'monto_descuento'=> $request->monto_descuento,
            'fecha_expiracion'=> date('Y-m-d H:i:s',strtotime($request->fecha_expiracion)),
            'stock'=> $request->stock,
            'estado'=> $request->estado,
            'cod_usuario'=> $user->id,
        ]);

        return new DescuentoVentaResource($datos);
    }

    public function show($id)
    {
        $datos = TbDescuentoVenta::find($id);

        if(is_null($datos)){
            return response()->json([
                'error'=> true,
                'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                'status'=> 404,'data'=> null],404);
        }

        return new DescuentoVentaResource($datos);
    }

    public function update(Request $request, $id)
    {
        $datos = TbDescuentoVenta::find($id);
        $datos->update($request->all());

        return new DescuentoVentaResource($datos);
    }

    public function destroy($id)
    {
        $datos = TbDescuentoVenta::find($id);
        $datos->delete();

        return response()->json(['message'=> 'Registro eliminado con éxito!'],200);
    }

    public function buscarcuponDescuento($numerocupon)
    {
        $datos = TbDescuentoVenta::where('numero_cupon',$numerocupon)
                                 ->where('estado','1')->get();

        if(count($datos) == 0){
            return response()->json([
                'error'=> true,
                'message'=> 'El codigo de cupón <b>'.$numerocupon.',</b> no es valido ó es posible que aya caduco.',
                'status'=> 404,'data'=> null],404);
        }else{
            if($datos[0]->stock == 0){
                $datos = TbDescuentoVenta::find($datos[0]->cod_descuento);
                $datos->estado = 0;
                $datos->update();
                return response()->json([
                    'error'=> true,
                    'message'=> 'El codigo de cupón  <b>'.$numerocupon.',</b> llego al limite de stock!',
                    'status'=> 404,'data'=> null],409);
            }
        }



        return response()->json($datos,200);
    }
}
