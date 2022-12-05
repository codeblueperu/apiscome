<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TbSucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_sucursals')->insert(
            ['cod_sucursal'=>1,
            'nombre_sucursal'=>'SCOME SKELL',
            'direccion'=>'Jr Meliton carbajal #240 - urb ingenieria - SMP',
            'telefono'=>'958044868',
            'email'=>'scomeskell@gmail.com',
            'logo'=>'logo.png',
            'ruc'=>'000000000',
            'estado'=>'ACTIVA',
        ]);
    }
}
