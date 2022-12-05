<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_personas', function (Blueprint $table) {
            $table->bigIncrements('cod_persona');
            $table->string('nombres',85)->unique();
            $table->string('numero_documento',12)->unique();
            $table->string('direccion',70)->nullable();
            $table->string('email',75);
            $table->integer('numero_telefono');
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('cod_usuario');
            $table->foreign('cod_usuario')->references('id')->on('users');
            $table->unsignedBigInteger('cod_tipo_documento');
            $table->foreign('cod_tipo_documento')->references('cod_tipo_documento')->on('tb_tipo_documentos');
            $table->unsignedBigInteger('cod_cargo');
            $table->foreign('cod_cargo')->references('cod_cargo')->on('tb_cargos');
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
        Schema::dropIfExists('tb_personas');
    }
}
