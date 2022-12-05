<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCliente extends Model
{
    use HasFactory;

    protected $table = 'tb_clientes';
    protected $primaryKey = 'cod_cliente';
    public $timestamps = false;

    protected $fillable = [
        'numero_documento',
        'ruc_cliente',
        'apellidos_nombres',
        'direccion',
        'telefono',
        'email',
        'observacion',
        'estado',
        'cod_tipo_documento'
    ];
}
