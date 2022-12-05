<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCargo extends Model
{
    use HasFactory;

    protected $table = 'tb_cargos';
    protected $primaryKey = 'cod_cargo';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_cargo',
        'estado',
    ];
}
