<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Solo aplica en MySQL — SQLite no soporta MODIFY COLUMN y acepta cualquier string
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('en_espera','revision','aprobado','rechazado') NOT NULL DEFAULT 'en_espera'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('borrador','enviado','aprobado','rechazado') NOT NULL DEFAULT 'borrador'");
        }
    }
};
