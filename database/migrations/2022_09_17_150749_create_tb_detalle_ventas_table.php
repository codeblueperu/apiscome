<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDetalleVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_detalle_ventas', function (Blueprint $table) {
            $table->bigIncrements('cod_detalle');
            $table->string('presentacion',20)->nullable();
            $table->string('color',12)->nullable();
            $table->decimal('precio_venta',18,2);
            $table->integer('cantidad');
            $table->decimal('descuento',18,2)->nullable();
            $table->decimal('total',18,2);
            $table->unsignedBigInteger('cod_compra');
            $table->foreign('cod_compra')->references('cod_compra')->on('tb_compra_productos');
            $table->unsignedBigInteger('cod_venta');
            $table->foreign('cod_venta')->references('cod_venta')->on('tb_ventas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_detalle_ventas');
    }
}
