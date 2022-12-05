<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_gastos', function (Blueprint $table) {
            $table->bigIncrements('cod_gasto');
            $table->string('descripcion_gasto',500);
            $table->decimal('monto_gasto',18,2);
            $table->datetime('fecha_registro');
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
        Schema::dropIfExists('tb_gastos');
    }
}
