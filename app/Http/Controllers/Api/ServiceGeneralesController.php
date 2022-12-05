<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbCargo;
use App\Models\TbMenu;
use App\Models\TbPermisoMenu;
use App\Models\TbPersona;
use App\Models\TbTipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceGeneralesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function listaTipoDocumentos()
    {
        try {
           $res = TbTipoDocumento::where('estado',1)->get();
           return response()->json(['data' => $res]);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function listaCargos()
    {
        try {
           $res = TbCargo::where('estado',1)->get();
           return response()->json(['data' => $res]);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function buscarPersonadni($dni)
    {
        try {
           $res = TbPersona::where('numero_documento',$dni)->get();
           if(count($res) > 0){
                return response()->json([
                    'message'=> 'El DNI ' . $dni . ', ya se encuentra registrado.'
                ],404);
           }
           return response()->json(['data' =>'disponible']);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function buscarPersonaEmail($email)
    {
        try {
           $res = TbPersona::where('email',$email)->get();
           if(count($res) > 0){
                return response()->json([
                    'message'=> 'El correo ' . $email . ', ya se encuentra registrado.'
                ],404);
           }
           return response()->json(['data' => 'disponible']);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function listaMenuPrincipal()
    {
        try {
           $menu = TbMenu::where('menu_principal',1)->get();
           return response()->json(['data' => $menu]);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function listaSubMenu($cargo,$grupo)
    {
        try {

           $dts = TbMenu::find($grupo);
           $menu = TbMenu::where('grupo_menu',$dts->grupo_menu)
#                            ->where('menu_principal','0')
                            ->get();
            $permisos = DB::select("SELECT pm.*,mn.grupo_menu FROM tb_permiso_menus pm, tb_menus mn where pm.cod_menu = mn.cod_menu and pm.cod_cargo = ? and mn.grupo_menu = ?", [$cargo,$dts->grupo_menu]);

           return response()->json(['data' => $menu,'permiso' => $permisos]);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }

    public function guardarPermisoMenu(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = json_decode($request->permisos);
            foreach($data as $item){
                DB::delete("DELETE FROM tb_permiso_menus WHERE cod_menu = ? AND cod_cargo = ?",[$item->cod_menu,$item->cod_cargo]);
            }

            foreach($data as $item){
                TbPermisoMenu::create([
                    'cod_menu' => $item->cod_menu,
                    'cod_cargo' => $item->cod_cargo,
                    'is_select' => $item->is_select,
                    'is_insert' => $item->is_insert,
                    'is_update' => $item->is_update,
                    'is_delete' => $item->is_delete,
                    'is_print' => $item->is_print,
                ]);
            }
            DB::commit();

           return response()->json(['data' => $data,'message' => 'Permisos otorgados con éxito.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }


}
