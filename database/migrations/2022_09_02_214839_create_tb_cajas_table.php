<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cajas', function (Blueprint $table) {
            $table->bigIncrements('cod_caja');
            $table->string('descripcion_caja',50);
            $table->datetime('fecha_apertura');
            $table->datetime('fecha_cierre')->nullable();
            $table->decimal('monto_inicial',18,2);
            $table->decimal('monto_efectivo',18,2)->nullable();
            $table->decimal('monto_digital',18,2)->nullable();
            $table->decimal('monto_ingreso',18,2)->nullable();
            $table->decimal('monto_gasto',18,2)->nullable();
            $table->decimal('monto_total',18,2)->nullable();
            $table->enum('estado',['APERTURADA','CERRADA'])->default('APERTURADA');
            $table->unsignedBigInteger('cod_sucursal');
            $table->foreign('cod_sucursal')->references('cod_sucursal')->on('tb_sucursals');
            $table->unsignedBigInteger('cod_usuario');
            $table->foreign('cod_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_cajas');
    }
}
