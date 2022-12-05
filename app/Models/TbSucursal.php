<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbSucursal extends Model
{
    use HasFactory;
    protected $table = 'tb_sucursals';
    protected $primaryKey = 'cod_sucursal';
    public $timestamps = false;

    protected $fillable = [
        'nombre_sucursal',
        'direccion',
        'telefono',
        'email',
        'logo',
        'ruc',
        'estado'
    ];

}
