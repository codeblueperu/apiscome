<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VWDetalleClienteDeuda extends Model
{
    use HasFactory;
    protected $table = 'VW_DETALLE_CLIENTE_DEUDA';
    protected $primaryKey = 'cod_detalle';
    public $timestamps = false;
    protected $fillable = [
        'apellidos_nombres',
        'telefono',
        'email',
        'fecha_venta',
        'monto_adelanto',
        'monto_debe',
        'total_deuda',
        'estado_compra',
        'cod_deuda',
        'cod_venta',
        'cod_detalle'
    ];
}
