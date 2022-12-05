<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbTipoComprobante extends Model
{
    use HasFactory;

    protected $table = 'tb_tipo_comprobantes';
    protected $primaryKey = 'cod_tipo_comprobante';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_corta',
        'descripcion_larga',
        'estado'
    ];
}
