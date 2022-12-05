<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbFamiliaCategoria extends Model
{
    use HasFactory;
    protected $table = 'tb_familia_categorias';
    protected $primaryKey = 'cod_familia_cat';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_familia',
        'estado'
    ];
}
