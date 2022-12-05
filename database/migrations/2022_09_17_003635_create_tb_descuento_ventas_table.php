<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDescuentoVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_descuento_ventas', function (Blueprint $table) {
            $table->bigIncrements('cod_descuento');
            $table->string('numero_cupon',12)->unique();
            $table->decimal('monto_descuento',18,2);
            $table->datetime('fecha_expiracion');
            $table->integer('stock');
            $table->boolean('estado')->default(1);
            $table->integer('cod_usuario');
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
        Schema::dropIfExists('tb_descuento_ventas');
    }
}
