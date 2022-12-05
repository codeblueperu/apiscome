<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_sucursals', function (Blueprint $table) {
            $table->bigIncrements('cod_sucursal');
            $table->string('nombre_sucursal',100);
            $table->string('direccion',100);
            $table->string('telefono',12);
            $table->string('email',65);
            $table->string('logo',20);
            $table->string('ruc',18);
            $table->string('estado')->default('ACTIVA');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_sucursals');
    }
}
