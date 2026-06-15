<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cobranza_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caso_id')->constrained('cobranza_casos')->cascadeOnDelete();
            $table->foreignId('actividad_origen_id')->nullable()->constrained('cobranza_actividades')->nullOnDelete();
            $table->foreignId('tipo_contacto_id')->nullable()->constrained('cobranza_catalogos')->nullOnDelete();
            $table->foreignId('accion_id')->nullable()->constrained('cobranza_catalogos')->nullOnDelete();
            $table->foreignId('tipo_respuesta_id')->nullable()->constrained('cobranza_catalogos')->nullOnDelete();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_programada');
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            $table->text('observacion')->nullable();
            $table->text('observacion_cierre')->nullable();
            $table->enum('estado', ['abierta', 'en_proceso', 'cerrada', 'cancelada'])->default('abierta');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cerrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('motivo_cancelacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobranza_actividades');
    }
};
