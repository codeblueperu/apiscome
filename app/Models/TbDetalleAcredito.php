<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDetalleAcredito extends Model
{
    use HasFactory;
    protected $table = 'tb_detalle_acreditos';
    protected $primaryKey = 'cod_detalle';
    public $timestamps = false;

    protected $fillable = [
        'cod_venta',
        'cod_deuda',
        'monto_adelanto',
        'monto_debe',
        'total_deuda',
        'estado'
    ];
}
