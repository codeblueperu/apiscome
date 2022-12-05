<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TbCargoSeeder::class,
            TbTipoDocumentoSeeder::class,
            UserSeeder::class,
            TbSucursalSeeder::class,
            TbPersonaSeeder::class,
            TbTipoPago::class,
            TbTipoComprobante::class,
            TbCorrelativoSeeder::class,
            TbClienteSeeder::class]
        );
    }
}
