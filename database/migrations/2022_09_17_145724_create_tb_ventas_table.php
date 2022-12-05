<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ventas', function (Blueprint $table) {
            $table->bigIncrements('cod_venta');
            $table->string('numero_comprobante',12);
            $table->string('numero_cupon',12)->nullable();
            $table->datetime('fecha_venta');
            $table->decimal('monto_subtotal',18,2);
            $table->decimal('monto_igv',18,2);
            $table->decimal('monto_descuento',18,2)->nullable();
            $table->decimal('monto_total',18,2);
            $table->boolean('estado_venta')->default(1);
            $table->boolean('estado_pago')->default(1);
            $table->integer('cod_usuario');
            $table->unsignedBigInteger('cod_tipo_comprobante');
            $table->foreign('cod_tipo_comprobante')->references('cod_tipo_comprobante')->on('tb_tipo_comprobantes');
            $table->unsignedBigInteger('cod_cliente');
            $table->foreign('cod_cliente')->references('cod_cliente')->on('tb_clientes');
            $table->unsignedBigInteger('cod_sucursal');
            $table->foreign('cod_sucursal')->references('cod_sucursal')->on('tb_sucursals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_ventas');
    }
}
