<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cobranza_catalogos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', [
                'tipo_contacto', 'accion', 'tipo_respuesta',
                'motivo_cierre', 'estado_caso', 'estado_actividad',
            ]);
            $table->string('codigo', 30);
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['tipo', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobranza_catalogos');
    }
};
