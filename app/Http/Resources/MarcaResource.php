<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarcaResource extends JsonResource
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
        return[
            'cod_marca' => $this->cod_marca,
            'descripcion_marca' => $this->descripcion_marca,
            'estado' => $this->estado,
            'urlLogo' => asset('/storage/images/logos/' . $this->logo),
            'cod_categoria' => $this->cod_categoria,
            'categoria' => $this->categoria
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }
}
