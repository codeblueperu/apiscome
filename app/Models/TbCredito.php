<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCredito extends Model
{
    use HasFactory;
    protected $table = 'tb_creditos';
    protected $primaryKey = 'cod_deuda';
    public $timestamps = false;

    protected $fillable = [
        'monto_credito',
        'monto_deuda',
        'estado',
        'cod_cliente',
        'cod_sucursal'
    ];

    public function cliente(){
        return $this->belongsTo(TbCliente::class,'cod_cliente');
    }

}
