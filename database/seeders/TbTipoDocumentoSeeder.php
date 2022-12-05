<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class TbTipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_tipo_documentos')->insert(['cod_tipo_documento'=>1,'descripcion_documento'=>'DNI']);
        DB::table('tb_tipo_documentos')->insert(['cod_tipo_documento'=>2,'descripcion_documento'=>'PTP']);
        DB::table('tb_tipo_documentos')->insert(['cod_tipo_documento'=>3,'descripcion_documento'=>'CARNET EXTRANJERIA']);
        DB::table('tb_tipo_documentos')->insert(['cod_tipo_documento'=>4,'descripcion_documento'=>'PASAPORTE']);
    }
}
