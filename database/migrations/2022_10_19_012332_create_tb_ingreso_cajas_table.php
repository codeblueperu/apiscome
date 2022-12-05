<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbIngresoCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ingreso_cajas', function (Blueprint $table) {
            $table->bigIncrements('cod_ingreso');
            $table->string('descripcion_ingreso',500);
            $table->decimal('monto_ingreso',18,2);
            $table->datetime('fecha_ingreso');
            $table->unsignedBigInteger('cod_sucursal');
            $table->foreign('cod_sucursal')->references('cod_sucursal')->on('tb_sucursals');
            $table->integer('cod_venta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_ingreso_cajas');
    }
}
