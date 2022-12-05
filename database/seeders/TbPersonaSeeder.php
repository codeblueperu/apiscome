<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TbPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_personas')->insert([
            'cod_persona'=>1,
            'nombres'=>'Jhony More Inga',
            'numero_documento'=>'48588250',
            'direccion'=>'Jr meliton Crbajal N° 240 - SMP',
            'email'=>'jhony13.more@gmail.com',
            'numero_telefono'=>'958044868',
            'avatar'=>'avata.png',
            'cod_usuario'=>1,
            'cod_tipo_documento'=>1,
            'cod_cargo'=>1,
            'cod_sucursal'=>1
        ]);
    }
}
