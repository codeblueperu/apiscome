<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'tb_detalle_ventas';
    protected $primaryKey = 'cod_detalle';
    public $timestamps = false;

    protected $fillable = [
        'presentacion',
        'color',
        'precio_venta',
        'cantidad',
        'descuento',
        'total',
        'cod_producto',
        'cod_compra',
        'cod_venta'
    ];
}
