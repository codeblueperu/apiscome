<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCompraProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_compra_productos', function (Blueprint $table) {
            $table->bigIncrements('cod_compra');
            $table->string('nombre_producto',200);
            $table->integer('stock_ingreso');
            $table->integer('stock_egreso');
            $table->decimal('precio_compra',18,2);
            $table->decimal('precio_venta_unidad',18,2);
            $table->decimal('precio_venta_cantidad',18,2);
            $table->integer('cantidad_venta_mayor');
            $table->string('numero_lote',25)->nullable();
            $table->date('fecha_compra')->nullable();
            $table->date('fecha_elaboracion')->nullable();
            $table->date('fecha_caduca')->nullable();
            $table->unsignedBigInteger('cod_producto');
            $table->foreign('cod_producto')->references('cod_producto')->on('tb_productos');
            $table->string('estado')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_compra_productos');
    }
}
