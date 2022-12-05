<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTipoPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_tipo_pagos', function (Blueprint $table) {
            $table->bigIncrements('cod_tipo_pago');
            $table->string('descripcion_larga',50);
            $table->string('descripcion_corta',20)->nullable();
            $table->string('numero_cuenta',30);
            $table->string('cci',30)->nullable();
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
        Schema::dropIfExists('tb_tipo_pagos');
    }
}
