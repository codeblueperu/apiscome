<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_creditos', function (Blueprint $table) {
            $table->bigIncrements('cod_deuda');
            $table->decimal('monto_credito',18,2);
            $table->decimal('monto_deuda',18,2);
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('tb_creditos');
    }
}
