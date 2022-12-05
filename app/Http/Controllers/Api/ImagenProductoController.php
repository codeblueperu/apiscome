<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TbImagenProducto;
use App\Http\Requests\ImagenProducto\StoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DetallaImageResource;

class ImagenProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index()
    {
        /* $image = TbImagenProducto::get();
        return response()->json([
            'error'=> false,
            'message'=> 'Lista de registros.',
            'status'=> 200,
            'data'=> $image
        ],200); */
        return DetallaImageResource::collection(TbImagenProducto::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $entidad = new TbImagenProducto();
        if($request->hasFile('image_name')){
            $imagen = $request->file('image_name');
            //$nombreimagen   =   Str::slug("nombre").time().'.'.$imagen->getClientOriginalExtension();
            $nombre_imagen = $imagen->getClientOriginalName();
            $destinationPath = "public/images/catalogo";
            Storage::putFileAs($destinationPath, $imagen, 'pd'.$nombre_imagen);

            $entidad->image_name = 'pd'.$nombre_imagen;
            $entidad->orden = $request->orden;
            $entidad->cod_producto = $request->cod_producto;
            $entidad->save();
        }
        //$image = TbImagenProducto::create($request->all());
        return response()->json([
            'error'=> false,
            'message'=> 'Imagen cargado con exito.',
            'status'=> 200,
            'data'=> $entidad
        ],200);
    }


    public function show($id)
    {
        $image = TbImagenProducto::find($id);
        //is_null()
        if(!$image){
            return response()->json([
                'error'=> true,
                'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                'status'=> 404,'data'=> null],404);
        }

        return new DetallaImageResource($image);

        /* return response()->json([
            'error'=> false,'message'=> 'Registro encontrado','status'=> 200,'data'=> $image
        ],200); */
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
        $image = TbImagenProducto::find($id);
        $image->update($request->all());

         return response()->json([
             'error'=> false,'message'=> 'Registro actualizado correctamente','status'=> 200,'data'=> $image->refresh()
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
        TbImagenProducto::where('cod_producto',$id)->delete();
       // $talla->delete();
        return response()->json([
            'error'=> false,'message'=> 'Registros eliminados correctamente','status'=> 200,'data'=> null
        ],200);
    }
}
