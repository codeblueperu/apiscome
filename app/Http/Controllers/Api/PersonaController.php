<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbPersona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PersonaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

   public function crearCuentaUsuarioSistema(Request $request){
     /*:::::::::::::::: INICIAMOS LA TRANSACCION ::::::::::::::*/
     DB::beginTransaction();
     $user = Auth::user();
     /* ::::::::::::::: ENGLOBAMOS TODO EN UN TRY ::::::::::::: */
    try {
        /* VAIDAMOS DNI */
        $dni = TbPersona::where('numero_documento',$request->dni)->get();
        if(count($dni) > 0){
             return response()->json([
                 'message'=> 'El DNI <b>' . $request->dni . '</b>, ya se encuentra registrado.'
             ],404);
        }

        /* VALIDAMOS USUARIO */
        $email = TbPersona::where('email',$request->email)->get();
        if(count($email) > 0){
             return response()->json([
                 'message'=> 'El correo <b>' . $request->email . '</b>, ya se encuentra registrado.'
             ],404);
        }

        /* CREAMOS UNA CUENTA USER LOGIN */
        $newuser = User::create([
            'email' => $request->email,
            'email_verified_at' => null,
            'password' => bcrypt($request->contrasenia),
            'remember_token' => null,
            'created_at' => null,
            'updated_at' => null,
        ]);

        /* ******************************* */
            $coduser = $newuser->id;
        /* ******************************* */

        $persona = TbPersona::create([
            'nombres' => $request->nombre,
            'numero_documento' => $request->dni,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'numero_telefono' => $request->celular,
            'avatar' => 'avatar.png',
            'cod_usuario' => $coduser,
            'cod_tipo_documento' => $request->cod_tipo_documento,
            'cod_cargo' => $request->cod_cargo,
            'cod_sucursal' => $request->cod_sucursal,
        ]);

         /* :::: LANZAMOS EL COMMIT :::::: */
         DB::commit();
         /* :::: RETORNAMOS LA RESPUESTA :::::: */
         return response()->json([ 'message' => 'Cuenta creada con éxito.','data' => $persona],200);
    } catch (\Throwable $e) {
        /* :::::::::::: REVERTIMOS TODO EN CASO DE UN ERROR::::: */
        DB::rollBack();
        /* :::::::::::: NOTIFICAR AL USUARIO EL ERROR :::::::::: */
        return response()->json([
            'code'=> $e->getCode(),
            'message'=> $e->getMessage()
        ],400);
    }
   }

   public function listaDatospersonales(){
        $persona = TbPersona::with(['tipoDocumento','cargo','sucursal'])->get();

        return response()->json([
            'error'=> false,
            'message'=> 'Lista de registros.',
            'status'=> 200,
            'data'=> $persona
        ],200);
   }

   public function buscarpersonaID($id){
        try {
            $data = TbPersona::with(['cuenta'])->find($id);
            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function editarDatosUsuario(Request $request,$id){
        /*:::::::::::::::: INICIAMOS LA TRANSACCION ::::::::::::::*/
        DB::beginTransaction();
        $user = Auth::user();
        /* ::::::::::::::: ENGLOBAMOS TODO EN UN TRY ::::::::::::: */
        try {
            /* ACUTALIZAMOS DATOS DE LA PERSONA */
            $persona = TbPersona::find($id);
            $persona->nombres = $request->nombre;
            $persona->numero_documento = $request->dni;
            $persona->direccion = $request->direccion;
            $persona->email = $request->email;
            $persona->numero_telefono = $request->celular;
            $persona->avatar = 'avatar.png';
            $persona->cod_usuario = $request->cod_usuario;
            $persona->cod_tipo_documento = $request->cod_tipo_documento;
            $persona->cod_cargo = $request->cod_cargo;
            $persona->cod_sucursal = $request->cod_sucursal;
            $persona->update();

            /* ACTUALIZAMOS DATOS DE LA CUENTA ACCESO */
            $user = User::find($request->cod_usuario);
            $user->email = $request->email;
            $user->password = ($request->contrasenia == 'SCOMEperu2022**' ? $user->password :  bcrypt($request->contrasenia)) ;
            $user->update();

            /* :::: LANZAMOS EL COMMIT :::::: */
            DB::commit();
            /* :::: RETORNAMOS LA RESPUESTA :::::: */
            return response()->json(['message' => 'Cuenta modificada con éxito.']);
        } catch (\Throwable $e) {
            /* :::::::::::: REVERTIMOS TODO EN CASO DE UN ERROR::::: */
            DB::rollBack();
            /* :::::::::::: NOTIFICAR AL USUARIO EL ERROR :::::::::: */
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }


    }

    public function eliminarCuenta($id){
        DB::beginTransaction();
        try {
            $data = TbPersona::find($id);
            $user = User::find($data->cod_usuario);
            $data->delete();
            $user->delete();

            DB::commit();

            return response()->json(['message' => 'Cuenta eliminado con éxito.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }
}
