<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDescuentoVenta extends Model
{
    use HasFactory;

    protected $table = 'tb_descuento_ventas';
    protected $primaryKey = 'cod_descuento';
    public $timestamps = false;

    protected $fillable = [
        'numero_cupon',
        'monto_descuento',
        'fecha_expiracion',
        'stock',
        'estado',
        'cod_usuario',
    ];
}
