<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDetalleColorProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_detalle_color_productos', function (Blueprint $table) {
            $table->bigIncrements('cod_color');
            $table->string('color',10);
            $table->integer('cantidad');
            $table->enum('estado',['DISPONIBLE','AGOTADO'])->default('DISPONIBLE');
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
        Schema::dropIfExists('tb_detalle_color_productos');
    }
}
