<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('submodulos')->where('slug', 'credito-cerrado')->delete();

        DB::table('submodulos')
            ->where('slug', 'credito-aprobado')
            ->update(['name' => 'Aprobado / Rechazado / Cerrado']);
    }

    public function down(): void
    {
        DB::table('submodulos')
            ->where('slug', 'credito-aprobado')
            ->update(['name' => 'Aprobado/Rechazado']);
    }
};
