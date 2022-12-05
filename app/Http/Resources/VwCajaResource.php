<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VwCajaResource extends JsonResource
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
            'dni_user' => $this->dni_user,
            'usuario' => $this->usuario,
            'descripcion_cargo' => $this->descripcion_cargo,
            'fecha_apertura' => $this->fecha_apertura,
            'fecha_cierre' => $this->fecha_cierre,
            'monto_inicial' => ($this->monto_inicial == '' ? 0.0 : $this->monto_inicial),
            'monto_efectivo' => ($this->monto_efectivo == '' ? 0.0 : $this->monto_efectivo),
            'monto_digital' => ($this->monto_digital == '' ? 0.0 : $this->monto_digital),
            'monto_ingreso' => ($this->monto_ingreso == '' ? 0.0 : $this->monto_ingreso),
            'monto_gasto' => ($this->monto_gasto == '' ? 0.0 : $this->monto_gasto),
            'estado' => $this->estado,
            'sucursal' => $this->sucursal,
            'cod_usuario' => $this->cod_usuario,
            'monto_cuadre_anterior' => ($this->monto_cuadre_anterior == '' ? 0.0 : $this->monto_cuadre_anterior),
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }
}
