<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbFichaTecnicaProducto extends Model
{
    use HasFactory;
    protected $table = 'tb_ficha_tecnica_productos';
    protected $primaryKey = 'cod_ficha';
    public $timestamps = false;

    protected $fillable = [
        'body_ficha',
        'cod_compra',
    ];
}
