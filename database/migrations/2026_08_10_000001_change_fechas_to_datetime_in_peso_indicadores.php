<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE peso_indicadores MODIFY fecha_inicio DATETIME NOT NULL');
            DB::statement('ALTER TABLE peso_indicadores MODIFY fecha_fin DATETIME NULL');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE peso_indicadores MODIFY fecha_inicio DATE NOT NULL');
            DB::statement('ALTER TABLE peso_indicadores MODIFY fecha_fin DATE NULL');
        }
    }
};
