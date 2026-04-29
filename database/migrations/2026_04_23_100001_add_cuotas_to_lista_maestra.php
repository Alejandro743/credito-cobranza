<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->unsignedSmallInteger('cantidad_cuotas')->nullable()->after('estado');
            $table->boolean('usa_cuota_inicial')->default(false)->after('cantidad_cuotas');
            $table->string('tipo_cuota_inicial', 20)->nullable()->after('usa_cuota_inicial');
            $table->decimal('valor_cuota_inicial', 10, 2)->nullable()->after('tipo_cuota_inicial');
        });
    }

    public function down(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->dropColumn(['cantidad_cuotas', 'usa_cuota_inicial', 'tipo_cuota_inicial', 'valor_cuota_inicial']);
        });
    }
};
