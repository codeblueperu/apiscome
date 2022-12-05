<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbFichaTecnicaProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ficha_tecnica_productos', function (Blueprint $table) {
            $table->bigIncrements('cod_ficha');
            $table->text('body_ficha');
            $table->unsignedBigInteger('cod_compra');
            $table->foreign('cod_compra')->references('cod_compra')->on('tb_compra_productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_ficha_tecnica_productos');
    }
}
