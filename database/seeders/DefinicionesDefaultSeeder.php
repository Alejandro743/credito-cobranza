<?php

namespace Database\Seeders;

use App\Models\PesoIndicador;
use App\Models\RangoCalificacion;
use Illuminate\Database\Seeder;

class DefinicionesDefaultSeeder extends Seeder
{
    public function run(): void
    {
        PesoIndicador::firstOrCreate(
            ['nombre' => 'Por Defecto'],
            [
                'fecha_inicio'        => '2000-01-01',
                'fecha_fin'           => null,
                'peso_puntualidad'    => 25,
                'peso_mora'           => 25,
                'peso_riesgo'         => 20,
                'peso_recuperacion'   => 20,
                'peso_reprogramacion' => 10,
                'activo'              => true,
            ]
        );

        RangoCalificacion::firstOrCreate(
            ['nombre' => 'Por Defecto'],
            [
                'fecha_inicio' => '2000-01-01',
                'fecha_fin'    => null,
                'min_a'        => 85,
                'min_b'        => 70,
                'min_c'        => 50,
                'min_d'        => 30,
                'activo'       => true,
            ]
        );
    }
}
