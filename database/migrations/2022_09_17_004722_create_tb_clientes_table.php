<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_clientes', function (Blueprint $table) {
            $table->bigIncrements('cod_cliente');
            $table->string('numero_documento',20);
            $table->string('ruc_cliente',18)->nullable();
            $table->string('apellidos_nombres',70);
            $table->string('direccion',70)->nullable();
            $table->string('telefono',12)->nullable();
            $table->string('email',70)->nullable();
            $table->string('observacion',200)->nullable();
            $table->boolean('estado')->default(1);
            $table->unsignedBigInteger('cod_tipo_documento');
            $table->foreign('cod_tipo_documento')->references('cod_tipo_documento')->on('tb_tipo_documentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_clientes');
    }
}
