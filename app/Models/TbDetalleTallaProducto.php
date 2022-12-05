<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDetalleTallaProducto extends Model
{
    use HasFactory;
    protected $table = 'tb_detalle_talla_productos';
    protected $primaryKey = 'cod_talla';
    public $timestamps = false;

    protected $fillable = [
        'talla',
        'cantidad',
        'estado',
        'cod_compra'
    ];
}
