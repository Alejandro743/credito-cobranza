<?php

namespace Database\Seeders;

use App\Models\MotivoCierre;
use Illuminate\Database\Seeder;

class MotivoCierreSeeder extends Seeder
{
    public function run(): void
    {
        $motivos = [
            ['nombre' => 'Fallecimiento',          'afecta_mora' => false],
            ['nombre' => 'Viaje / Paradero desconocido', 'afecta_mora' => false],
            ['nombre' => 'Incumplimiento de pago', 'afecta_mora' => true],
            ['nombre' => 'Acuerdo de cancelación', 'afecta_mora' => false],
            ['nombre' => 'Otro',                   'afecta_mora' => false],
        ];

        foreach ($motivos as $m) {
            MotivoCierre::firstOrCreate(
                ['nombre' => $m['nombre']],
                ['afecta_mora' => $m['afecta_mora'], 'activo' => true]
            );
        }
    }
}
