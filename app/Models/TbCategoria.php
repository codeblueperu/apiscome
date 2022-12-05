<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCategoria extends Model
{
    use HasFactory;
    protected $table = 'tb_categorias';
    protected $primaryKey = 'cod_categoria';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_categoria',
        'estado',
        'cod_familia_cat',
    ];

    /* protected $hidden = [
        'cod_familia_cat'
    ]; */

    public function familia(){
        return $this->belongsTo(TbFamiliaCategoria::class,'cod_familia_cat');
    }

}
