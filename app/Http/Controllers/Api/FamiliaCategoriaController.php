<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\FamiliaCategoria\StoreRequest;

use App\Models\TbFamiliaCategoria;
use App\Http\Requests\FamiliaCategoria\UpdateRequest;

class FamiliaCategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function listarTodo()
    {
        $familia = TbFamiliaCategoria::get();

        return response()->json([
            'error'=> false,
            'message'=> 'Lista de registros.',
            'status'=> 200,
            'data'=> $familia
        ],200);
    }


    public function registrar(StoreRequest $request)
    {
        try {
            $familia = TbFamiliaCategoria::create($request->all());
            return response()->json([
                'error'=> false,
                'message'=> 'Registro creado con exito.',
                'status'=> 200,
                'data'=> $familia
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }

    }


    public function buscarID($id)
    {
        try {
            $familia = TbFamiliaCategoria::find($id);
            if(!$familia){
                return response()->json([
                    'error'=> true,
                    'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                    'status'=> 404,'data'=> null],404);
            }

            return response()->json([
                'error'=> false,'message'=> 'Registro encontrado','status'=> 200,'data'=> $familia
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }
    }


    public function update(UpdateRequest $request, $id)
    {
        try {
            $familia = TbFamiliaCategoria::find($id);
            $familia->update($request->all());

            return response()->json([
                'error'=> false,'message'=> 'Registro actualizado correctamente','status'=> 200,'data'=> $familia
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }

    }


    public function destroy($id)
    {
        try {
            $familia = TbFamiliaCategoria::find($id);
            $familia->delete();

            return response()->json([
                'error'=> false,'message'=> 'Registro eliminado correctamente','status'=> 200,'data'=> $familia
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }

    }
}
