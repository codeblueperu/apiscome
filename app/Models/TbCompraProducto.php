<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCompraProducto extends Model
{
    use HasFactory;

    protected $table = 'tb_compra_productos';
    protected $primaryKey = 'cod_compra';
    public $timestamps = false;

    protected $fillable = [
        'nombre_producto',
        'stock_ingreso',
        'stock_egreso',
        'precio_compra',
        'precio_venta_unidad',
        'precio_venta_cantidad',
        'cantidad_venta_mayor',
        'numero_lote',
        'fecha_compra',
        'fecha_elaboracion',
        'fecha_caduca',
        'cod_producto',
        'estado'
    ];

   /*  protected $hidden = [
        'cod_producto',
    ]; */

  /*   public function tallas(){
        return $this->hasMany(TbDetalleTallaProducto::class,'cod_compra');
    }

    public function colores(){
        return $this->hasMany(TbDetalleColorProducto::class,'cod_compra');
    }
    public function fichatecnica(){
        return $this->hasOne(TbFichaTecnicaProducto::class,'cod_compra');
    } */

   /*  public function Images(){
        return $this->hasMany(TbImagenProducto::class,'cod_producto');
    } */
}
