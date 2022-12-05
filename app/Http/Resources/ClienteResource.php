<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "cod_cliente" => $this->cod_cliente,
            "numero_documento"=> $this->numero_documento,
            "ruc_cliente" => $this->ruc_cliente,
            "apellidos_nombres"=>$this->apellidos_nombres,
            "direccion"=>$this->direccion,
            "telefono"=>$this->telefono,
            "email"=>$this->email,
            "observacion"=>$this->observacion,
            "estado"=>$this->estado,
            "cod_tipo_documento"=>$this->cod_tipo_documento,
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }
}
