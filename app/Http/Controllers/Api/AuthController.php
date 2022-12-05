<?php

namespace App\Http\Controllers\Api;

use App\Models\TbCargo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\CreateCountRequest;
use App\Models\TbPersona;
use App\Http\Requests\User\AuthRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Promise\Create;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getLogin','createcountuser']]);
    }

    public function createcountuser(CreateCountRequest $request){
        //CREAMOS LOS DATOS DE LOGIN
        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        //REGISTRAMOS LOS DATOS PERSONALES DEL USUARIO
        $persona = new TbPersona();
        $persona->nombres = $request->nombres;
        $persona->numero_documento = $request->numero_documento;
        $persona->direccion = $request->direccion;
        $persona->email = $request->email;
        $persona->numero_telefono = $request->numero_telefono;
        $persona->avatar = 'avatar.png';
        $persona->cod_usuario = $user->id;
        $persona->cod_tipo_documento = $request->cod_tipo_documento;
        $persona->cod_cargo = $request->cod_cargo;
        $persona->save();
        //$user->roles()->attach($request->roles);

        //$token = JWTAuth::fromUser($user);

        return response()->json([
            'error'=>false,
            'message'=>'El usuario: '.$request->nombres .', fue registrado con exito.',
            'data'=> $persona,
            'status'=>200
        ],200);
    }


    public function getLogin(AuthRequest  $request){

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => true,
                'message'=> 'El usuario y/o contraseña ingresado son incorrectos.',
                'status' => 403], 403);
        }
        $user = User::with('datospersonales')->where('email',$request->email)->first();

        return response()->json([
            'error'=>true,
            'message'=>'Acceso correcto',
            'token'=>$this->respondWithToken($token),
            'data'=>$user,
            'menus'=>$this->menusUsuarioLogin(),
            'status'=>200
        ],200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Session cerrada con exito.'],200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'access_expires_in' => auth()->factory()->getTTL() * 60,
            'refresh_token' => auth()
		->claims([
			'xtype' => 'refresh',
			'xpair' => auth()->payload()->get('jti')
			])
			->setTTL(auth()->factory()->getTTL() * 3)
			->tokenById(auth()->user()->id),
		'refresh_expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    public function listausuarioSucursal($codsucursal)
    {
        try {
            $data = TbPersona::where('cod_sucursal',$codsucursal)->get();

            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function menusUsuarioLogin()
    {
        try {
            $userLogin = Auth::user();

            $sql_menuPrincipal = "select mn.descripcion_corta,mn.icon_menu,mn.grupo_menu,per.cod_persona
            from tb_permiso_menus pm,tb_menus mn,tb_cargos cg,tb_personas per
            where pm.cod_menu = mn.cod_menu and pm.cod_cargo = cg.cod_cargo and cg.cod_cargo = per.cod_cargo and
            per.cod_persona = ".$userLogin->id." and mn.menu_principal = 1 and pm.is_select = 1";

            $menu_pricipal = DB::select($sql_menuPrincipal);
            $jsonarray =  array();
            foreach($menu_pricipal as $value){

                /* BUSCAMOS LOS SUBMENU DEL MENU PRINCIPAL */
                $sql_submenu = "select mn.descripcion_corta,mn.icon_menu,pm.is_select,pm.is_insert,pm.is_update,
                pm.is_delete,pm.is_print,mn.ruta_menu from tb_permiso_menus pm,tb_menus mn,tb_cargos cg,tb_personas per
                where pm.cod_menu = mn.cod_menu and pm.cod_cargo = cg.cod_cargo and cg.cod_cargo = per.cod_cargo and
                per.cod_persona = ".$userLogin->id." and mn.menu_principal = 0 AND mn.grupo_menu = '".$value->grupo_menu."' and pm.is_select = 1";

                $data_submenu = DB::select($sql_submenu);
                $submenu = array();
                foreach ($data_submenu as $key){
                    $submenu[] =  array(
                        "submenu" => $key->descripcion_corta,
                        "icon" => $key->icon_menu,
                        "is_select" => $key->is_select,
                        "is_insert" => $key->is_insert,
                        "is_update" => $key->is_update,
                        "is_delete" => $key->is_delete,
                        "is_print" => $key->is_print,
                        "link" => $key->ruta_menu
                    );
                }

                $jsonarray[] = array(
                    "menu"  => $value->descripcion_corta,
                    "icono" => $value->icon_menu,
                    "grupo" => $value->grupo_menu,
                    "submenu" => $submenu
                );
            }


            return $jsonarray;

        } catch (\Throwable $th) {
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }
    }

}

