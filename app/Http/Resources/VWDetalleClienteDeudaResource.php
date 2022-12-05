<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VWDetalleClienteDeudaResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'apellidos_nombres' => $this->apellidos_nombres,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'fecha_venta' => $this->fecha_venta,
            'monto_adelanto' => $this->monto_adelanto,
            'monto_debe' => $this->monto_debe,
            'total_deuda' => $this->total_deuda,
            'estado_compra' => $this->estado_compra,
            'cod_deuda' => $this->cod_deuda,
            'cod_venta' => $this->cod_venta,
            'cod_detalle' => $this->cod_detalle
        ];
    }

    public function with($request){
        return [
            'res'=> true,
        ];
    }
}
