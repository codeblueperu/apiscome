<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoPagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'cod_tipo_pago' => $this->cod_tipo_pago,
            'descripcion_larga' => $this->descripcion_larga,
            'descripcion_corta' => $this->descripcion_corta,
            'numero_cuenta' => $this->numero_cuenta,
            'cci' => $this->cci,
            'estado' => $this->estado,
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }
}
