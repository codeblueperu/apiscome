<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_productos', function (Blueprint $table) {
            $table->bigIncrements('cod_producto');
            $table->string('code_barra',25)->nullable();
            $table->string('nombre_producto',100);
            $table->string('descripcion_corta',100)->nullable();
            $table->string('descripcion_larga',100)->nullable();
            $table->unsignedBigInteger('cod_categoria');
            $table->foreign('cod_categoria')->references('cod_categoria')->on('tb_categorias');
            $table->unsignedBigInteger('cod_marca');
            $table->foreign('cod_marca')->references('cod_marca')->on('tb_marcas');
            $table->unsignedBigInteger('cod_sucursal');
            $table->foreign('cod_sucursal')->references('cod_sucursal')->on('tb_sucursals');
            $table->string('estado')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_productos');
    }
}
