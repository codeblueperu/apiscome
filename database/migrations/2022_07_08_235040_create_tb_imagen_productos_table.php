<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbImagenProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_imagen_productos', function (Blueprint $table) {
            $table->bigIncrements('cod_image');
            $table->string('image_name',25);
            $table->integer('orden');
            $table->enum('estado',['DISPONIBLE','NOTFOUND']);
            $table->unsignedBigInteger('cod_producto');
            $table->foreign('cod_producto')->references('cod_producto')->on('tb_productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_imagen_productos');
    }
}
