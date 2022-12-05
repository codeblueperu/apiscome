<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDetalleAcreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_detalle_acreditos', function (Blueprint $table) {
            $table->bigIncrements('cod_detalle');
            $table->unsignedBigInteger('cod_venta');
            $table->foreign('cod_venta')->references('cod_venta')->on('tb_ventas');
            $table->unsignedBigInteger('cod_deuda');
            $table->foreign('cod_deuda')->references('cod_deuda')->on('tb_creditos');
            $table->decimal('monto_adelanto',18,2);
            $table->decimal('monto_debe',18,2);
            $table->decimal('total_deuda',18,2);
            $table->boolean('estado')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_detalle_acreditos');
    }
}
