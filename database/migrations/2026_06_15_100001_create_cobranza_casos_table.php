<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cobranza_casos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->unique()->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('asignado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_asignacion')->nullable();
            $table->enum('estado', ['sin_asignar', 'asignado', 'en_gestion', 'cerrado', 'cancelado'])->default('sin_asignar');
            $table->text('observacion_asignacion')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            $table->foreignId('cerrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('motivo_cierre')->nullable();
            $table->text('observacion_cierre')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobranza_casos');
    }
};
