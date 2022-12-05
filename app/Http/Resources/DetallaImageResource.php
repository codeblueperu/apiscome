<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetallaImageResource extends JsonResource
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
            "cod_image" => $this->cod_image,
            "cod_producto"=> $this->cod_producto,
            "estado" => $this->estado,
            "image_name"=>$this->image_name,
            "url_image"=>asset('/storage/images/catalogo/' . $this->image_name)
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }
}
