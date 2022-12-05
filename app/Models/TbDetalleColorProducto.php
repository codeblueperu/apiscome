<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDetalleColorProducto extends Model
{
    use HasFactory;
    protected $table = 'tb_detalle_color_productos';
    protected $primaryKey = 'cod_color';
    public $timestamps = false;

    protected $fillable = [
        'color',
        'cantidad',
        'estado',
        'cod_compra'
    ];
}
