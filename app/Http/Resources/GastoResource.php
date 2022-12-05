<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GastoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'cod_gasto' => $this->cod_gasto,
            'descripcion_gasto' => $this->descripcion_gasto,
            'monto_gasto' => $this->monto_gasto,
            'fecha_registro' => $this->fecha_registro,
            'cod_sucursal' => $this->cod_sucursal
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }
}
