<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbTipoPago extends Model
{
    use HasFactory;

    protected $table = 'tb_tipo_pagos';
    protected $primaryKey = 'cod_tipo_pago';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_larga',
        'descripcion_corta',
        'numero_cuenta',
        'cci',
        'estado'
    ];
}
