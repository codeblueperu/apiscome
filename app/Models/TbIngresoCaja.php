<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbIngresoCaja extends Model
{
    use HasFactory;
    protected $table = 'tb_ingreso_cajas';
    protected $primaryKey = 'cod_ingreso';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_ingreso',
        'monto_ingreso',
        'fecha_ingreso',
        'cod_sucursal',
        'cod_venta'
    ];
}
