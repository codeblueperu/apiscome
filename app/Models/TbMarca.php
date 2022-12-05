<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMarca extends Model
{
    use HasFactory;
    protected $table = 'tb_marcas';
    protected $primaryKey = 'cod_marca';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_marca',
        'logo',
        'estado',
        'cod_categoria'
    ];

    public function categoria(){
        return $this->belongsTo(TbCategoria::class,'cod_categoria');
    }
}
