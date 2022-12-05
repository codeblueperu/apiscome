<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FichaTecnica\StoreRequest;
use App\Models\TbFichaTecnicaProducto;

class FichaTecnicaController extends Controller
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
        $fecha = TbFichaTecnicaProducto::get();
        return response()->json([
            'error'=> false,
            'message'=> 'Lista de registros.',
            'status'=> 200,
            'data'=> $fecha
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
        $ficha = TbFichaTecnicaProducto::create($request->all());
        return response()->json([
            'error'=> false,
            'message'=> 'La Ficha Técnica fue registrado con exito.',
            'status'=> 200,
            'data'=> $ficha
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
        $ficha = TbFichaTecnicaProducto::where('cod_producto',$id)->get();
        //is_null()
        if(!$ficha){
            return response()->json([
                'error'=> true,
                'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                'status'=> 404,'data'=> null],404);
        }

        return response()->json([
            'error'=> false,'message'=> 'Registro encontrado','status'=> 200,'data'=> $ficha
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
        $color = TbFichaTecnicaProducto::find($id);
        $color->update($request->all());

         return response()->json([
             'error'=> false,'message'=> 'Registro actualizado correctamente','status'=> 200,'data'=> $color->refresh()
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
        TbFichaTecnicaProducto::where('cod_producto',$id)->delete();
       // $talla->delete();
        return response()->json([
            'error'=> false,'message'=> 'Registros eliminados correctamente','status'=> 200,'data'=> null
        ],200);
    }
}
