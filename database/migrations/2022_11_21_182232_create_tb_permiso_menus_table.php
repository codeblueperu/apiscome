<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPermisoMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_permiso_menus', function (Blueprint $table) {
            $table->bigIncrements('cod_permiso');
            $table->unsignedBigInteger('cod_menu');
            $table->foreign('cod_menu')->references('cod_menu')->on('tb_menus');
            $table->unsignedBigInteger('cod_cargo');
            $table->foreign('cod_cargo')->references('cod_cargo')->on('tb_cargos');
            $table->integer('is_select');
            $table->integer('is_insert');
            $table->integer('is_update');
            $table->integer('is_delete');
            $table->integer('is_print');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_permiso_menus');
    }
}
