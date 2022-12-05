<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCuponProducto extends Model
{
    use HasFactory;
    protected $table = 'tb_cupon_productos';
    protected $primaryKey = 'cod_cupon';
    public $timestamps = false;

    protected $fillable = [
        'numero_cupon',
        'fecha_inicio',
        'fecha_termino',
        'stock',
        'valor_descuento',
        'estado',
        'cod_producto',
    ];

    public function producto(){
        return $this->hasMany(TbProducto::class,'cod_producto');
    }
}
