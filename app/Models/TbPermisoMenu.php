<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbPermisoMenu extends Model
{
    use HasFactory;
    protected $table = 'tb_permiso_menus';
    protected $primaryKey = 'cod_permiso';
    public $timestamps = false;

    protected $fillable = [
        'cod_menu',
        'cod_cargo',
        'is_select',
        'is_insert',
        'is_update',
        'is_delete',
        'is_print'
    ];

}
