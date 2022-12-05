<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marca\StoreRequest;
use App\Http\Requests\Marca\UpdateRequest;
use App\Models\TbMarca;
use Illuminate\Http\Request;
use App\Http\Resources\MarcaResource;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index()
    {
        return MarcaResource::collection(TbMarca::with(['categoria'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $entidad = new TbMarca();
        if($request->hasFile('logo')){
            $imagen = $request->file('logo');
            $nombre_imagen = $imagen->getClientOriginalName();
            $destinationPath = "public/images/logos";
            Storage::putFileAs($destinationPath, $imagen, 'logo'.$nombre_imagen);

            $entidad->logo = 'logo'.$nombre_imagen;
            $entidad->descripcion_marca = $request->descripcion_marca;
            $entidad->cod_categoria = $request->cod_categoria;
            $entidad->save();
        }else{
            $entidad->logo = 'default.png';
            $entidad->descripcion_marca = $request->descripcion_marca;
            $entidad->cod_categoria = $request->cod_categoria;
            $entidad->save();
        }
        return response()->json([
            'error'=> false,
            'message'=> 'Marca registrada con exito.',
            'status'=> 200,
            'data'=> $entidad
        ],200);
    }

    public function show($id)
    {
        $marca = TbMarca::find($id);
        //is_null()
        if(!$marca){
            return response()->json([
                'error'=> true,
                'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                'status'=> 404,'data'=> null],404);
        }


        return  new MarcaResource($marca);
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
        $marca = TbMarca::find($id);
        $nameImage = null;
        if($request->hasFile('logo')){
            $imagen = $request->file('logo');
            $nombre_imagen = $imagen->getClientOriginalName();
            $destinationPath = "public/images/logos";
            Storage::putFileAs($destinationPath, $imagen, 'logo'.$nombre_imagen);
            $nameImage = 'logo'.$nombre_imagen;
        }else{
            $nameImage = $marca->logo;
        }

        TbMarca::where('cod_marca', $id)
                ->update(array('descripcion_marca' => $request->descripcion_marca,
                         'logo' => $nameImage,'cod_categoria' => $request->cod_categoria));

        return response()->json([
             'error'=> false,'message'=> 'Registro actualizado correctamente.','status'=> 200,'data'=> $marca->refresh()
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
        $marca = TbMarca::find($id);
        $marca->delete();

        return response()->json([
            'error'=> false,'message'=> 'Registro eliminado correctamente','status'=> 200,'data'=> $marca
        ],200);
    }

    public function getBuscarPorCategorias($codcategoria)
    {
        //$marca = TbMarca::where('cod_categoria', $codcategoria)->get();
        return MarcaResource::collection(TbMarca::where('cod_categoria', $codcategoria)->get());
    }
}
