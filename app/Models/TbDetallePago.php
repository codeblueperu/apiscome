<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDetallePago extends Model
{
    use HasFactory;

    protected $table = 'tb_detalle_pagos';
    protected $primaryKey = 'cod_detalle_pago';
    public $timestamps = false;

    protected $fillable = [
        'codigo_operacion',
        'monto',
        'cod_tipo_pago',
        'cod_venta'
    ];
}
