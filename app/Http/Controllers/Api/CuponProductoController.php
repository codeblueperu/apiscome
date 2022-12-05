<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TbCuponProducto;
use App\Http\Requests\CuponProducto\StoreRequest;

class CuponProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cupon = TbCuponProducto::with(['producto'])->get();
        return response()->json([
            'error'=> false,
            'message'=> 'Lista de registros.',
            'status'=> 200,
            'data'=> $cupon
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $cupon = TbCuponProducto::create($request->all());
        return response()->json([
            'error'=> false,
            'message'=> 'Cupon creado',
            'status'=> 200,
            'data'=> $cupon
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $cupon = TbCuponProducto::find($id);
        //is_null()
        if(!$cupon){
            return response()->json([
                'error'=> true,
                'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                'status'=> 404,'data'=> null],404);
        }

        return response()->json([
            'error'=> false,'message'=> 'Registro encontrado','status'=> 200,'data'=> $cupon
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreRequest $request, $id)
    {
        $cupon = TbCuponProducto::find($id);
        $cupon->update($request->all());

         return response()->json([
             'error'=> false,'message'=> 'Registro actualizado correctamente','status'=> 200,'data'=> $cupon->refresh()
         ],200);
    }


    public function destroy($id)
    {
        $talla = TbCuponProducto::find($id);
        $talla->delete();
        return response()->json([
            'error'=> false,'message'=> 'Registros eliminados correctamente','status'=> 200,'data'=> null
        ],200);
    }
}
