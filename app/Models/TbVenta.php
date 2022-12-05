<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbVenta extends Model
{
    use HasFactory;

    protected $table = 'tb_ventas';
    protected $primaryKey = 'cod_venta';
    public $timestamps = false;

    protected $fillable = [
        'numero_comprobante',
        'numero_cupon',
        'fecha_venta',
        'monto_subtotal',
        'monto_igv',
        'monto_descuento',
        'monto_total',
        'estado_venta',
        'estado_pago',
        'cod_usuario',
        'cod_tipo_comprobante',
        'cod_cliente',
        'cod_sucursal'
    ];
}
