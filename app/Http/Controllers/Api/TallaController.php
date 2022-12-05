<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tallas\StoreRequest;
use App\Http\Requests\Tallas\UpdateRequest;
use App\Models\TbDetalleTallaProducto;
use Illuminate\Http\Request;

class TallaController extends Controller
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
        $tallas = TbDetalleTallaProducto::get();
        return response()->json([
            'error'=> false,
            'message'=> 'Lista de registros.',
            'status'=> 200,
            'data'=> $tallas
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
        $tallas = TbDetalleTallaProducto::create($request->all());
        return response()->json([
            'error'=> false,
            'message'=> 'Talla registrada con exito.',
            'status'=> 200,
            'data'=> $tallas
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
        $talla = TbDetalleTallaProducto::where('cod_producto',$id)->get();
        //is_null()
        if(!$talla){
            return response()->json([
                'error'=> true,
                'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                'status'=> 404,'data'=> null],404);
        }

        return response()->json([
            'error'=> false,'message'=> 'Registro encontrado','status'=> 200,'data'=> $talla
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        $talla = TbDetalleTallaProducto::find($id);
        $talla->update($request->all());

         return response()->json([
             'error'=> false,'message'=> 'Registro actualizado correctamente','status'=> 200,'data'=> $talla->refresh()
         ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        TbDetalleTallaProducto::where('cod_producto',$id)->delete();
       // $talla->delete();
        return response()->json([
            'error'=> false,'message'=> 'Registros eliminados correctamente','status'=> 200,'data'=> null
        ],200);
    }
}
