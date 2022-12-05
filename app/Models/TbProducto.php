<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbProducto extends Model
{
    use HasFactory;
    protected $table = 'tb_productos';
    protected $primaryKey = 'cod_producto';
    public $timestamps = false;

    protected $fillable = [
        'code_barra',
        'nombre_producto',
        'descripcion_corta',
        'descripcion_larga',
        'cod_categoria',
        'cod_marca',
        'cod_sucursal',
        'estado',
    ];

    protected $hidden = [
        'cod_categoria',
        'cod_marca',
        'cod_sucursal',
    ];

    public function categoria(){
        return $this->belongsTo(TbCategoria::class,'cod_categoria');
    }

    public function marca(){
        return $this->belongsTo(TbMarca::class,'cod_marca');
    }

    /* public function tallas(){
        return $this->hasMany(TbDetalleTallaProducto::class,'cod_producto');
    }

    public function colores(){
        return $this->hasMany(TbDetalleColorProducto::class,'cod_producto');
    }
    public function fichatecnica(){
        return $this->hasOne(TbFichaTecnicaProducto::class,'cod_producto');
    }*/
    public function Images(){
        return $this->hasMany(TbImagenProducto::class,'cod_producto');
    }
}
