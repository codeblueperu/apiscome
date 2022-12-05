<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbGasto extends Model
{
    use HasFactory;
    protected $table = 'tb_gastos';
    protected $primaryKey = 'cod_gasto';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_gasto',
        'monto_gasto',
        'fecha_registro',
        'cod_sucursal'
    ];
}
