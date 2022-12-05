<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCorrelativosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_correlativos', function (Blueprint $table) {
            $table->bigIncrements('cod_correlativo');
            $table->string('abreviatura',20);
            $table->integer('correlativo');
            $table->boolean('estado')->default(1);
            $table->unsignedBigInteger('cod_tipo_comprobante');
            $table->foreign('cod_tipo_comprobante')->references('cod_tipo_comprobante')->on('tb_tipo_comprobantes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_correlativos');
    }
}
