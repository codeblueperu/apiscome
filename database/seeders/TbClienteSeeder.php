<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TbClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_clientes')->insert([
            'cod_cliente' => 1,
            'numero_documento' => '87654321',
            'ruc_cliente' => '0987654321',
            'apellidos_nombres' => 'CLIENTE DIVERSOS',
            'direccion' => '-',
            'telefono'=> '-',
            'email' => '-',
            'observacion' => '-',
            'estado' => 1,
            'cod_tipo_documento' => 1
        ]);
    }
}
