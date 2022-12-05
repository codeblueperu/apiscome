<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDetallePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_detalle_pagos', function (Blueprint $table) {
            $table->bigIncrements('cod_detalle_pago');
            $table->string('codigo_operacion',30)->nullable();
            $table->decimal('monto',18,2);
            $table->unsignedBigInteger('cod_tipo_pago');
            $table->foreign('cod_tipo_pago')->references('cod_tipo_pago')->on('tb_tipo_pagos');
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
        Schema::dropIfExists('tb_detalle_pagos');
    }
}
