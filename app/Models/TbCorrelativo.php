<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCorrelativo extends Model
{
    use HasFactory;
    protected $table = 'tb_correlativos';
    protected $primaryKey = 'cod_correlativo';
    public $timestamps = false;

    protected $fillable = [
        'abreviatura',
        'correlativo',
        'estado',
        'cod_tipo_comprobante'
    ];




}
