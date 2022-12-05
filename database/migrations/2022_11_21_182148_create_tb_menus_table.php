<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_menus', function (Blueprint $table) {
            $table->bigIncrements('cod_menu');
            $table->string('descripcion_corta',100);
            $table->string('descripcion_larga',300);
            $table->string('grupo_menu',5);
            $table->integer('menu_principal');
            $table->string('icon_menu',100);
            $table->boolean('estado')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_menus');
    }
}
