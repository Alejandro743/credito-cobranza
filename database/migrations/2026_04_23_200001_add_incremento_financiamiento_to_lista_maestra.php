<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->string('tipo_incremento', 20)->nullable()->after('estado');
            $table->decimal('valor_incremento', 8, 2)->default(0)->after('tipo_incremento');
            $table->unsignedSmallInteger('dias_entre_cuotas')->nullable()->after('cantidad_cuotas');
        });
    }

    public function down(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->dropColumn(['tipo_incremento', 'valor_incremento', 'dias_entre_cuotas']);
        });
    }
};
