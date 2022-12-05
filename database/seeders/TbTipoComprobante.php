<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TbTipoComprobante extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_tipo_comprobantes')->insert(
            ['cod_tipo_comprobante'=>1,
            'descripcion_corta'=>'TICKET DE VENTA',
            'descripcion_larga'=>'TICKET DE VENTA - SIN VALOR TRIBUTARIO',
            'estado'=>1
        ]);
    }
}
