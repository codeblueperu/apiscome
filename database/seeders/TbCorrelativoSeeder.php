<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TbCorrelativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_correlativos')->insert([
            'cod_correlativo'=>1,
            'abreviatura'=>'TICK-',
            'correlativo'=>0,
            'estado'=>1,
            'cod_tipo_comprobante'=>1
        ]);
    }
}
