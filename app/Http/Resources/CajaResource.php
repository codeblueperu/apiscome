<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CajaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "cod_caja" => $this->cod_caja,
            'descripcion_caja' => $this->descripcion_caja,
            'fecha_apertura' => $this->fecha_apertura,
            'fecha_cierre' => $this->fecha_cierre,
            'monto_inicial' => $this->monto_inicial,
            'monto_efectivo' => $this->monto_efectivo,
            'monto_digital' => $this->monto_digital,
            'monto_ingreso' => $this->monto_ingreso,
            'monto_gasto' => $this->monto_gasto,
            'monto_total' => $this->monto_total,
            'estado' => $this->estado,
            'cod_sucursal' => $this->cod_sucursal,
            'cod_usuario' => $this->cod_usuario
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }

}
