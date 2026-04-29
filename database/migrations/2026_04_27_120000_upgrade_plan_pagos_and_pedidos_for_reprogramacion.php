<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── plan_pagos: quitar unique (si existe), agregar version/estado/notas
        Schema::table('plan_pagos', function (Blueprint $table) {
            // Columnas idempotentes — solo se agregan si aún no existen
            if (!Schema::hasColumn('plan_pagos', 'version')) {
                $table->unsignedSmallInteger('version')->default(1)->after('pedido_id');
            }
            if (!Schema::hasColumn('plan_pagos', 'estado')) {
                $table->string('estado', 20)->default('activo')->after('version');
            }
            if (!Schema::hasColumn('plan_pagos', 'notas')) {
                $table->text('notas')->nullable()->after('total_pagar');
            }
        });
        // pedidos.estado: SQLite almacena enums como string; no requiere ALTER.
        // Los nuevos valores 'operativo' y 'cerrado' se aceptan directamente.
    }

    public function down(): void
    {
        Schema::table('plan_pagos', function (Blueprint $table) {
            $table->dropColumn(['version', 'estado', 'notas']);
        });
    }
};
