<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GastoResource;
use App\Models\TbGasto;
use Illuminate\Http\Request;

class GastoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }
    public function buscarporfecha($fechainit,$fechafin)
    {
        $gasto = TbGasto::whereBetween('fecha_registro', [$fechainit,$fechafin])->get()->sortDesc();
        return GastoResource::collection($gasto);
    }


    public function store(Request $request)
    {
        return new GastoResource(TbGasto::create($request->all()));
    }


    public function show($id)
    {
        $gasto = TbGasto::find($id);

        return new GastoResource($gasto);
    }


    public function update(Request $request, $id)
    {
        $gasto = TbGasto::find($id);

        $gasto->update($request->all());
    }


    public function destroy($id)
    {
        $gasto = TbGasto::find($id);
        $gasto->delete();
    }
}
