<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbImagenProducto extends Model
{
    use HasFactory;
    protected $table = 'tb_imagen_productos';
    protected $primaryKey = 'cod_image';
    public $timestamps = false;

    protected $fillable = [
        'image_name',
        'orden',
        'estado',
        'cod_producto',
    ];
}
