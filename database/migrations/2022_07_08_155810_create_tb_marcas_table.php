<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMarcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_marcas', function (Blueprint $table) {
            $table->bigIncrements('cod_marca');
            $table->string('descripcion_marca',35);
            $table->string('logo')->nullable();
            $table->boolean('estado')->default('1');
            $table->unsignedBigInteger('cod_categoria');
            $table->foreign('cod_categoria')->references('cod_categoria')->on('tb_categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_marcas');
    }
}
