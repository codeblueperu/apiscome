<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DescuentoVentaResource extends JsonResource
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
            "cod_descuento" => $this->cod_descuento,
            "numero_cupon"=> $this->numero_cupon,
            "monto_descuento" => $this->monto_descuento,
            "fecha_expiracion"=>$this->fecha_expiracion,
            "stock"=>$this->stock,
            "estado"=>$this->estado,
            "cod_usuario"=>$this->cod_usuario,
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }
}
