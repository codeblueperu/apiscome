<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class TbCargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_cargos')->insert(['cod_cargo'=>1,'descripcion_cargo'=>'Administrador']);
        DB::table('tb_cargos')->insert(['cod_cargo'=>2,'descripcion_cargo'=>'Visitante']);
    }
}
