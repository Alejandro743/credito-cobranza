<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cobranza_catalogos', function (Blueprint $table) {
            $table->enum('tipo', [
                'tipo_contacto', 'accion', 'tipo_respuesta',
                'motivo_cierre', 'estado_caso', 'estado_actividad', 'tipo_cancelacion',
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('cobranza_catalogos', function (Blueprint $table) {
            $table->enum('tipo', [
                'tipo_contacto', 'accion', 'tipo_respuesta',
                'motivo_cierre', 'estado_caso', 'estado_actividad',
            ])->change();
        });
    }
};
