<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCuponProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cupon_productos', function (Blueprint $table) {
            $table->bigIncrements('cod_cupon');
            $table->string('numero_cupon',20);
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_termino');
            $table->integer('stock');
            $table->decimal('valor_descuento',18,2);
            $table->enum('estado',['VIGENTE','EXPIRO'])->default('VIGENTE');
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
        Schema::dropIfExists('tb_cupon_productos');
    }
}
