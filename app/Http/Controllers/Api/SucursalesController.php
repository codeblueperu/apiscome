<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbSucursal;
use Illuminate\Http\Request;

class SucursalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function listasucursales()
    {
         try {
           $res = TbSucursal::where('estado','ACTIVA')->get();
           return response()->json(['data' => $res]);
        } catch (\Throwable $e) {
            return response()->json([
                'code'=> $e->getCode(),
                'message'=> $e->getMessage()
            ],400);
        }
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
