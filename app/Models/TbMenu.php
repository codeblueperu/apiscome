<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMenu extends Model
{
    use HasFactory;
    protected $table = 'tb_menus';
    protected $primaryKey = 'cod_menu';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_corta',
        'descripcion_larga',
        'grupo_menu',
        'menu_principal',
        'icon_menu',
        'estado'
    ];
}
