<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermisosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
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


            return response()->json([
                'status'=> 200,'data' => $jsonarray
           ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'code'=> $th->getCode(),
                'message'=> $th->getMessage()
            ],400);
        }
    }


}
