<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCaja extends Model
{
    use HasFactory;
    protected $table = 'tb_cajas';
    protected $primaryKey = 'cod_caja';
    public $timestamps = false;
    protected $fillable = [
        'descripcion_caja',
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'monto_efectivo',
        'monto_digital',
        'monto_ingreso',
        'monto_gasto',
        'monto_total',
        'estado',
        'cod_sucursal',
        'cod_usuario'
    ];
}
