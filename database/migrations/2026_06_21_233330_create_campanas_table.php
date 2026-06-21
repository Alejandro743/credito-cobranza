<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campanas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('estado', ['abierta', 'en_proceso', 'cerrada', 'cancelada'])->default('abierta');
            $table->unsignedBigInteger('tipo_contacto_id')->nullable();
            $table->unsignedBigInteger('accion_id')->nullable();
            $table->date('fecha_programada')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('creado_por')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->foreign('tipo_contacto_id')->references('id')->on('cobranza_catalogos')->nullOnDelete();
            $table->foreign('accion_id')->references('id')->on('cobranza_catalogos')->nullOnDelete();
            $table->foreign('responsable_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('creado_por')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campanas');
    }
};
