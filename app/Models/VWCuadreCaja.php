<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VWCuadreCaja extends Model
{
    use HasFactory;
    protected $table = 'VW_CUADRAR_CAJA_CHICA';
    protected $primaryKey = 'cod_caja';
    public $timestamps = false;
    protected $fillable = [
        'cod_caja',
        'dni_user',
        'usuario',
        'descripcion_cargo',
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'monto_efectivo',
        'monto_digital',
        'monto_gasto',
        'estado',
        'sucursal',
        'cod_usuario',
        'monto_cuadre_anterior'
    ];
}
