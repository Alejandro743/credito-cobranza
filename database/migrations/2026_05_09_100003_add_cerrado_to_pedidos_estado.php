<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite almacena enums como string — acepta cualquier valor sin ALTER
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('en_espera','revision','aprobado','rechazado','cerrado') NOT NULL DEFAULT 'en_espera'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::table('pedidos')->where('estado', 'cerrado')->update(['estado' => 'aprobado']);
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('en_espera','revision','aprobado','rechazado') NOT NULL DEFAULT 'en_espera'");
        }
    }
};
