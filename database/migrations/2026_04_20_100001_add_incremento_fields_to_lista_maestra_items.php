<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lista_maestra_items', function (Blueprint $table) {
            $table->enum('tipo_incremento', ['porcentaje', 'monto_fijo'])->nullable()->after('descuento');
            $table->decimal('factor_incremento', 10, 2)->default(0)->after('tipo_incremento');
            $table->decimal('monto_incremento', 10, 2)->default(0)->after('factor_incremento');
        });
    }

    public function down(): void
    {
        Schema::table('lista_maestra_items', function (Blueprint $table) {
            $table->dropColumn(['tipo_incremento', 'factor_incremento', 'monto_incremento']);
        });
    }
};
