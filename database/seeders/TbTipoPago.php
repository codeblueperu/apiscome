<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TbTipoPago extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_tipo_pagos')->insert(
            ['cod_tipo_pago'=>1,
            'descripcion_larga'=>'EFECTIVO',
            'descripcion_corta'=>'CASH',
            'numero_cuenta'=>'000-000-000',
            'cci'=>'0000-0000-0000',
            'estado'=>1
        ]);

        DB::table('tb_tipo_pagos')->insert(
            ['cod_tipo_pago'=>2,
            'descripcion_larga'=>'TRANSFERENCIA',
            'descripcion_corta'=>'TRANSFERENCIA',
            'numero_cuenta'=>'000-000-000',
            'cci'=>'0000-0000-0000',
            'estado'=>1
        ]);

        DB::table('tb_tipo_pagos')->insert(
            ['cod_tipo_pago'=>3,
            'descripcion_larga'=>'YAPE',
            'descripcion_corta'=>'YAPE',
            'numero_cuenta'=>'958044868',
            'cci'=>'958044868',
            'estado'=>1
        ]);

        DB::table('tb_tipo_pagos')->insert(
            ['cod_tipo_pago'=>4,
            'descripcion_larga'=>'PLIN',
            'descripcion_corta'=>'PLIN',
            'numero_cuenta'=>'958044868',
            'cci'=>'958044868',
            'estado'=>1
        ]);

        DB::table('tb_tipo_pagos')->insert(
            ['cod_tipo_pago'=>5,
            'descripcion_larga'=>'TUNKI',
            'descripcion_corta'=>'TUNKI',
            'numero_cuenta'=>'958044868',
            'cci'=>'958044868',
            'estado'=>1
        ]);
    }
}
