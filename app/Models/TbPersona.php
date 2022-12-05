<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbPersona extends Model
{
    use HasFactory;

    protected $table = 'tb_personas';
    protected $primaryKey = 'cod_persona';
    public $timestamps = false;

    protected $fillable = [
        'nombres',
        'numero_documento',
        'direccion',
        'email',
        'numero_telefono',
        'avatar',
        'cod_usuario',
        'cod_cargo',
        'cod_tipo_documento',
        'cod_sucursal'
    ];

    /* protected $hidden = [
        'cod_usuario',
        'cod_tipo_documento',
        'cod_cargo'
    ]; */

    public function cuenta(){
        return $this->belongsTo(User::class,'cod_usuario');
    }

    public function tipoDocumento(){
        return $this->belongsTo(TbTipoDocumento::class,'cod_tipo_documento');
    }

    public function cargo(){
        return $this->belongsTo(TbCargo::class,'cod_cargo');
    }

    public function sucursal(){
        return $this->belongsTo(TbSucursal::class,'cod_sucursal');
    }
}
