<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_categorias', function (Blueprint $table) {
            $table->bigIncrements('cod_categoria');
            $table->string('descripcion_categoria',45);
            $table->boolean('estado')->default('1');
            $table->unsignedBigInteger('cod_familia_cat');
            $table->foreign('cod_familia_cat')->references('cod_familia_cat')->on('tb_familia_categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_categorias');
    }
}
