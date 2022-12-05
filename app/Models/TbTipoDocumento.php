<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbTipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tb_tipo_documentos';
    protected $primaryKey = 'cod_tipo_documento';
    public $timestamps = false;
    protected $fillable = [
        'descripcion_documento',
        'estado'
    ];
}
