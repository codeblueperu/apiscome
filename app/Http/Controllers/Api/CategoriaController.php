<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\StoreRequest;
use App\Http\Requests\Categoria\UpdateRequest;
use App\Models\TbCategoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
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
        $categoria = TbCategoria::with(['familia'])->get();
        return response()->json([
            'error'=> false,
            'message'=> 'Lista de registros.',
            'status'=> 200,
            'data'=> $categoria
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
        $categoria = TbCategoria::create($request->all());
        return response()->json([
            'error'=> false,
            'message'=> 'Categoria registrada con exito.',
            'status'=> 200,
            'data'=> $categoria
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
        $categoria = TbCategoria::with(['familia'])->find($id);
        //is_null()
        if(!$categoria){
            return response()->json([
                'error'=> true,
                'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                'status'=> 404,'data'=> null],404);
        }

        return response()->json([
            'error'=> false,'message'=> 'Registro encontrado','status'=> 200,'data'=> $categoria
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
        $categoria = TbCategoria::with(['familia'])->find($id);
        $categoria->update($request->all());

         return response()->json([
             'error'=> false,'message'=> 'Registro actualizado correctamente','status'=> 200,'data'=> $categoria->refresh()
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
        $categoria = TbCategoria::find($id);
        $categoria->delete();

        return response()->json([
            'error'=> false,'message'=> 'Registro eliminado correctamente','status'=> 200,'data'=> $categoria
        ],200);
    }

    public function obtenerCatFamilia($idfamilia){
        $categoria = TbCategoria::where('cod_familia_cat',$idfamilia)->get();

        return response()->json([
            'data' => $categoria
        ],200);
    }
}
