<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ClienteResource;
use App\Models\TbCliente;

class TbClienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index()
    {
        return ClienteResource::collection(TbCliente::all());
    }

    public function store(Request $request)
    {
        $datos = TbCliente::create($request->all());
        return new ClienteResource($datos);
    }

    public function show($id)
    {
        $datos = TbCliente::find($id);

        if(is_null($datos)){
            return response()->json([
                'error'=> true,
                'message'=> 'El registro con ID: '.$id.', no se encuentro registrado en la Base de Datos.',
                'status'=> 404,'data'=> null],404);
        }

        return new ClienteResource($datos);
    }

    public function update(Request $request, $id)
    {
        $datos = TbCliente::find($id);
        $datos->update($request->all());

        return new ClienteResource($datos);
    }

    public function destroy($id)
    {
        $datos = TbCliente::find($id);
        $datos->delete();

        return response()->json(['message'=> 'Registro eliminado con éxito!'],200);
    }

    public function bucarClienteNumeroDocumento($numerodocumento)
    {
        $datos = new TbCliente();
        $datos = TbCliente::where('numero_documento',$numerodocumento)
                                 ->where('estado','1')->get();
        if(count($datos) == 0){
            $token = 'af33a94afe0a0f80bf54da41f610509040eb8cfcd3f1de23c658de3a09da6818';
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apiperu.dev/api/dni/".$numerodocumento."?api_token=".$token,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return response()->json(['error' => $err,'message' => 'Error al buscar en ApiPeru.'],500);
            } else {
                try {
                    $dt = new TbCliente();
                    $reniec = json_decode($response);
                    //return response()->json( $reniec,409);

                    if(isset($reniec->message)){
                        return response()->json( $reniec,409);
                    }else{
                        $dt->numero_documento = $reniec->data->numero;
                        $dt->ruc_cliente = $reniec->data->numero;
                        $dt->apellidos_nombres = $reniec->data->nombre_completo;
                        $dt->direccion = '-';
                        $dt->telefono = '-';
                        $dt->email = '-';
                        $dt->observacion = 'APIPERU - OBTENIDO';
                        $dt->estado = 1;
                        $dt->cod_tipo_documento = 1;
                        $dt->save();
                    return response()->json(  $dt->refresh(),200);
                    }

                } catch (\Throwable $th) {
                    throw $th;
                }

            }
        }
        return response()->json($datos[0],200);
    }


}
