<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndUsersSeeder::class,              // roles + usuarios de prueba
            ModulosSubmodulosSeeder::class,          // 4 módulos y sus submódulos
            RolesPermisosDefaultSeeder::class,       // permisos iniciales por rol
            CiudadesProvinciaMunicipioSeeder::class, // Bolivia: ciudades, provincias, municipios
        ]);
    }
}
